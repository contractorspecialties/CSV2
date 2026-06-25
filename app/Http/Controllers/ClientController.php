<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class ClientController extends Controller
{
    /**
     * Display the mobile-first client roster list.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Resolve the custom database prefix automatically (e.g., sc_clients)
        $userTable = (new User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';
        $clientTable = $prefix . 'clients';

        // Make sure the database table exists and is ready for field data
        $this->ensureSchemaIsHealed($clientTable);

        // Fetch clients tied to this company, sorting newest jobs to the top
        $query = DB::table($clientTable)->where('company_id', $user->company_id);

        // Quick filter for searching by name or city while on site
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('client_name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('project_type', 'like', "%{$search}%");
            });
        }

        $clients = $query->orderBy('updated_at', 'desc')->get();

        // Add easy-to-use helpers for mobile map strings and clipboard sharing text
        $clients->transform(function ($client) {
            // Build a full unmapped address string for quick navigation tracking
            $fullAddress = trim("{$client->street_address} {$client->city} {$client->state} {$client->zip_code}");
            $client->google_maps_url = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($fullAddress);

            // Text payload that a contractor can text to a subcontractor (Hides customer name/phone for privacy)
            $client->subcontractor_share_text = "PROJECT DETAILS:\n" .
                "Type: " . ($client->project_type ?? 'General Trade') . "\n" .
                "Location: " . ($client->city ?? 'Local Area') . ", " . ($client->zip_code ?? '') . "\n" .
                "Work Scope: " . ($client->project_description ?? 'See attached file details.');

            return $client;
        });

        return view('workspace.crm.index', compact('clients'));
    }

    /**
     * Store a brand-new client profile entry.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'client_name'         => 'required|string|max:255',
            'phone_number'        => 'required|string|max:50',
            'email_address'       => 'nullable|email|max:255',
            'street_address'      => 'nullable|string|max:255',
            'city'                => 'nullable|string|max:100',
            'state'               => 'nullable|string|max:50',
            'zip_code'            => 'nullable|string|max:20',
            'project_type'        => 'required|string|max:150', // e.g., Deck Build, Metal Roof, Tile Repair
            'project_description' => 'nullable|string|max:2000',
            'job_status'          => 'required|in:lead,active,completed,paused',
        ]);

        $userTable = (new User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';
        $clientTable = $prefix . 'clients';

        $this->ensureSchemaIsHealed($clientTable);

        DB::table($clientTable)->insert([
            'company_id'          => $user->company_id,
            'client_name'         => $validated['client_name'],
            'phone_number'        => $validated['phone_number'],
            'email_address'       => $validated['email_address'],
            'street_address'      => $validated['street_address'],
            'city'                => $validated['city'],
            'state'               => $validated['state'] ?? 'NC',
            'zip_code'            => $validated['zip_code'],
            'project_type'        => $validated['project_type'],
            'project_description' => $validated['project_description'],
            'job_status'          => $validated['job_status'],
            'customer_notes'      => null,
            'created_at'          => now(),
            'updated_at'          => now(),
        ]);

        Log::info("📌 New client file logged successfully by User ID: {$user->id}");

        return redirect()->route('workspace.crm.index')->with('status', '👍 Client profile added to roster.');
    }

    /**
     * Append field updates or timestamped site notes to a client file.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'client_name'         => 'required|string|max:255',
            'phone_number'        => 'required|string|max:50',
            'email_address'       => 'nullable|email|max:255',
            'street_address'      => 'nullable|string|max:255',
            'city'                => 'nullable|string|max:100',
            'state'               => 'nullable|string|max:50',
            'zip_code'            => 'nullable|string|max:20',
            'project_type'        => 'required|string|max:150',
            'project_description' => 'nullable|string|max:2000',
            'job_status'          => 'required|in:lead,active,completed,paused',
            'new_site_note'       => 'nullable|string|max:1000', // Capture immediate updates from the field
        ]);

        $userTable = (new User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';
        $clientTable = $prefix . 'clients';

        $this->ensureSchemaIsHealed($clientTable);

        $client = DB::table($clientTable)
            ->where('id', $id)
            ->where('company_id', $user->company_id)
            ->first();

        if (!$client) {
            abort(403, 'Unauthorized client records manipulation bypass.');
        }

        // Manage and format running site journal notes
        $runningNotes = $client->customer_notes ?? '';
        if (!empty($validated['new_site_note'])) {
            $timestamp = date('m/d/Y g:i A');
            $newEntry = "[{$timestamp} - Added from Field]: " . trim($validated['new_site_note']);
            $runningNotes = empty($runningNotes) ? $newEntry : $newEntry . "\n\n" . $runningNotes;
        }

        DB::table($clientTable)
            ->where('id', $id)
            ->update([
                'client_name'         => $validated['client_name'],
                'phone_number'        => $validated['phone_number'],
                'email_address'       => $validated['email_address'],
                'street_address'      => $validated['street_address'],
                'city'                => $validated['city'],
                'state'               => $validated['state'],
                'zip_code'            => $validated['zip_code'],
                'project_type'        => $validated['project_type'],
                'project_description' => $validated['project_description'],
                'job_status'          => $validated['job_status'],
                'customer_notes'      => $runningNotes,
                'updated_at'          => now(),
            ]);

        return redirect()->route('workspace.crm.index')->with('status', '⚡ Client record file updated.');
    }

    /**
     * Safe Plugin-Driven Table Self-Healing Structural Guard.
     */
    private function ensureSchemaIsHealed(string $tableName): void
    {
        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('company_id')->index();
                $table->string('client_name', 255);
                $table->string('phone_number', 50);
                $table->string('email_address', 255)->nullable();
                $table->string('street_address', 255)->nullable();
                $table->string('city', 100)->nullable();
                $table->string('state', 50)->default('NC');
                $table->string('zip_code', 20)->nullable();
                $table->string('project_type', 150)->nullable(); // e.g., Framing Repair, Landscape Install
                $table->text('project_description')->nullable();
                $table->string('job_status', 50)->default('lead'); // lead, active, completed, paused
                $table->text('customer_notes')->nullable(); // Chronological timestamped notes block
                $table->timestamps();
            });
        } else {
            // Self-healing columns checker if you add features later
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'project_type')) {
                    $table->string('project_type', 150)->nullable()->after('zip_code');
                }
            });
        }
    }
}
