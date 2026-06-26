<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class ClientController extends Controller
{
    /**
     * Display the prioritized CRM directory loop with active lookup variables.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Resolve custom database prefix configurations dynamically (e.g., sc_clients)
        $userTable = (new User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';
        $clientTable = $prefix . 'clients';

        // Self-heal the schema structure safely
        $this->ensureSchemaIsHealed($clientTable);

        $query = DB::table($clientTable)->where('company_id', $user->company_id);

        $search = '';
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function($q) use ($search) {
                // Multi-column safety search fallback
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  // Support old table names if search runs before cache drops
                  ->orWhere('client_name', 'like', "%{$search}%");
            });
        }

        // Order by updated_at so contractors always see recently touched files first
        $rawClients = $query->orderBy('updated_at', 'desc')->get();

        // 🛡️ INDESTRUCTIBLE DATA NORMALIZATION COMPATIBILITY LAYER
        // Maps old table structures and new layout targets seamlessly so Blade never throws an undefined property error
        $clients = $rawClients->map(function ($client) {
            $normalized = new \stdClass();
            $normalized->id = $client->id;
            $normalized->company_id = $client->company_id;

            // Core Identity Normalization
            $normalized->name = $client->name ?? $client->client_name ?? 'Unknown Client';
            $normalized->company = $client->company ?? $client->company_name ?? '';

            // Communication Lines Normalization
            $normalized->phone = $client->phone ?? $client->phone_number ?? '';
            $normalized->email = $client->email ?? $client->email_address ?? '';

            // Address System Normalization
            if (isset($client->address)) {
                $normalized->address = $client->address;
            } else {
                $street = $client->street_address ?? '';
                $city = $client->city ?? '';
                $state = $client->state ?? '';
                $zip = $client->zip_code ?? '';
                $normalized->address = trim("{$street} {$city} {$state} {$zip}");
            }

            // Notes & Logs Normalization
            $normalized->notes = $client->notes ?? $client->customer_notes ?? $client->project_description ?? 'No specific job site notes saved yet.';

            return $normalized;
        });

        return view('workspace.crm.index', compact('clients', 'search'));
    }

    /**
     * Render fast field context customer creation card.
     */
    public function create()
    {
        return view('workspace.crm.create');
    }

    /**
     * Commit a fresh target account profile inline.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'notes'   => 'nullable|string|max:2000'
        ]);

        $userTable = (new User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';
        $clientTable = $prefix . 'clients';

        $this->ensureSchemaIsHealed($clientTable);

        // Double-write array structure to support legacy or newly patched schemas safely
        $insertData = [
            'company_id' => $user->company_id,
            'name'       => $validated['name'],
            'company'    => $validated['company'],
            'email'      => $validated['email'],
            'phone'      => $validated['phone'],
            'address'    => $validated['address'],
            'notes'      => $validated['notes'],
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if (Schema::hasColumn($clientTable, 'client_name')) { $insertData['client_name'] = $validated['name']; }
        if (Schema::hasColumn($clientTable, 'phone_number')) { $insertData['phone_number'] = $validated['phone']; }
        if (Schema::hasColumn($clientTable, 'email_address')) { $insertData['email_address'] = $validated['email']; }
        if (Schema::hasColumn($clientTable, 'customer_notes')) { $insertData['customer_notes'] = $validated['notes']; }

        DB::table($clientTable)->insert($insertData);

        Log::info("静态 field client profile saved to list by User ID: {$user->id}");

        return redirect()->route('workspace.crm.index')->with('status', '⚡ New customer profile successfully saved to your list.');
    }

    /**
     * Render inline CRM profile edit views securely.
     */
    public function edit($id)
    {
        $user = Auth::user();
        $userTable = (new User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';
        $clientTable = $prefix . 'clients';

        $client = DB::table($clientTable)
            ->where('id', $id)
            ->where('company_id', $user->company_id)
            ->first();

        if (!$client) {
            abort(404, 'Client target data row missing.');
        }

        // Normalize single data model for edit frame rendering safely
        $normalized = new \stdClass();
        $normalized->id = $client->id;
        $normalized->name = $client->name ?? $client->client_name ?? '';
        $normalized->company = $client->company ?? $client->company_name ?? '';
        $normalized->phone = $client->phone ?? $client->phone_number ?? '';
        $normalized->email = $client->email ?? $client->email_address ?? '';
        $normalized->notes = $client->notes ?? $client->customer_notes ?? $client->project_description ?? '';

        if (isset($client->address)) {
            $normalized->address = $client->address;
        } else {
            $street = $client->street_address ?? '';
            $city = $client->city ?? '';
            $state = $client->state ?? '';
            $zip = $client->zip_code ?? '';
            $normalized->address = trim("{$street} {$city} {$state} {$zip}");
        }

        return view('workspace.crm.edit', compact('client', 'normalized'));
    }

    /**
     * Process inline CRM profile edits securely.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:50',
            'address' => 'nullable|string|max:500',
            'notes'   => 'nullable|string|max:2000'
        ]);

        $userTable = (new User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';
        $clientTable = $prefix . 'clients';

        $this->ensureSchemaIsHealed($clientTable);

        $exists = DB::table($clientTable)
            ->where('id', $id)
            ->where('company_id', $user->company_id)
            ->exists();

        if (!$exists) {
            abort(403, 'Unauthorized database write bypass flagged.');
        }

        $updateData = [
            'name'       => $validated['name'],
            'company'    => $validated['company'],
            'email'      => $validated['email'],
            'phone'      => $validated['phone'],
            'address'    => $validated['address'],
            'notes'      => $validated['notes'],
            'updated_at' => now(),
        ];

        if (Schema::hasColumn($clientTable, 'client_name')) { $updateData['client_name'] = $validated['name']; }
        if (Schema::hasColumn($clientTable, 'phone_number')) { $updateData['phone_number'] = $validated['phone']; }
        if (Schema::hasColumn($clientTable, 'email_address')) { $updateData['email_address'] = $validated['email']; }
        if (Schema::hasColumn($clientTable, 'customer_notes')) { $updateData['customer_notes'] = $validated['notes']; }

        DB::table($clientTable)->where('id', $id)->update($updateData);

        return redirect()->route('workspace.crm.index')->with('status', '🔄 Customer profile details successfully updated.');
    }

    /**
     * Purge a customer profile record from the tenant partition.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $userTable = (new User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';
        $clientTable = $prefix . 'clients';

        DB::table($clientTable)
            ->where('id', $id)
            ->where('company_id', $user->company_id)
            ->delete();

        return redirect()->route('workspace.crm.index')->with('status', '🗑️ Customer account cleanly scrubbed from company directory registries.');
    }

    /**
     * Stream memory-isolated CSV data backups using modern architecture structures.
     */
    public function exportCsv()
    {
        $user = Auth::user();
        $userTable = (new User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';
        $clientTable = $prefix . 'clients';

        $this->ensureSchemaIsHealed($clientTable);

        $rawClients = DB::table($clientTable)
            ->where('company_id', $user->company_id)
            ->orderBy('name', 'asc')
            ->get();

        $fileName = 'client_list_export_' . date('Y-m-d_His') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Client Name', 'Company', 'Email Address', 'Phone Number', 'Site Address', 'Notes'];

        $callback = function() use($rawClients, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($rawClients as $client) {
                $name = $client->name ?? $client->client_name ?? 'Unknown';
                $company = $client->company ?? $client->company_name ?? 'N/A';
                $phone = $client->phone ?? $client->phone_number ?? 'N/A';
                $email = $client->email ?? $client->email_address ?? 'N/A';
                $notes = $client->notes ?? $client->customer_notes ?? $client->project_description ?? '';

                if (isset($client->address)) {
                    $address = $client->address;
                } else {
                    $street = $client->street_address ?? '';
                    $city = $client->city ?? '';
                    $state = $client->state ?? '';
                    $zip = $client->zip_code ?? '';
                    $address = trim("{$street} {$city} {$state} {$zip}");
                }

                fputcsv($file, [$client->id, $name, $company, $email, $phone, $address, $notes]);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Safe Plugin-Driven Database Self-Healing Structural Guard.
     */
    private function ensureSchemaIsHealed(string $tableName): void
    {
        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->index();
                $table->string('name', 255)->nullable();
                $table->string('company', 255)->nullable();
                $table->string('phone', 50)->nullable();
                $table->string('email', 255)->nullable();
                $table->text('address')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        } else {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'name')) { $table->string('name', 255)->nullable()->after('company_id'); }
                if (!Schema::hasColumn($tableName, 'company')) { $table->string('company', 255)->nullable()->after('name'); }
                if (!Schema::hasColumn($tableName, 'phone')) { $table->string('phone', 50)->nullable()->after('company'); }
                if (!Schema::hasColumn($tableName, 'email')) { $table->string('email', 255)->nullable()->after('phone'); }
                if (!Schema::hasColumn($tableName, 'address')) { $table->text('address')->nullable()->after('email'); }
                if (!Schema::hasColumn($tableName, 'notes')) { $table->text('notes')->nullable()->after('address'); }
            });
        }
    }
}
