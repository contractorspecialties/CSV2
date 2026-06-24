<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;

class CompanyProfileController extends Controller
{
    /**
     * Render the internal contractor-facing profile configuration workspace.
     */
    public function edit()
    {
        $user = Auth::user();

        // Resolve prefix mapping safely matching your database context
        $userTable = (new User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';
        $companyTable = $prefix . 'companies';

        // Run self-healing schema patcher recursively on execution loops
        $this->ensureSchemaIsHealed($companyTable);

        $company = DB::table($companyTable)->where('id', $user->company_id)->first();

        // Decode the gallery array defensively for front-end loops
        $galleryImages = [];
        if (!empty($company->gallery_paths)) {
            $galleryImages = json_decode($company->gallery_paths, true) ?? [];
        }

        return view('workspace.profile', compact('company', 'galleryImages'));
    }

    /**
     * Process validation and persist trust hierarchy metrics safely.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'company_bio'           => 'nullable|string|max:1000',
            'work_philosophy'       => 'nullable|string|max:1000',
            'years_in_business'     => 'nullable|integer|min:0|max:100',
            'license_number'        => 'nullable|string|max:100',
            'insurance_badge'       => 'nullable|boolean',
            'typical_response_time' => 'required|string|max:100',
            'warranty_details'      => 'nullable|string|max:255',
            'new_gallery_images.*'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'remove_images'         => 'nullable|array',
        ]);

        $userTable = (new User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';
        $companyTable = $prefix . 'companies';

        // Run self-healing schema patcher recursively on execution loops
        $this->ensureSchemaIsHealed($companyTable);

        $company = DB::table($companyTable)->where('id', $user->company_id)->first();
        $currentGallery = !empty($company->gallery_paths) ? json_decode($company->gallery_paths, true) : [];

        // 1. Process image removals if selected
        if (!empty($validated['remove_images'])) {
            foreach ($validated['remove_images'] as $imageToRemove) {
                if (($key = array_search($imageToRemove, $currentGallery)) !== false) {
                    unset($currentGallery[$key]);
                    // Delete file physically if it exists
                    if (file_exists(public_path($imageToRemove))) {
                        @unlink(public_path($imageToRemove));
                    }
                }
            }
            $currentGallery = array_values($currentGallery); // Re-index arrays
        }

        // 2. Process multi-file showcase image uploads (Max 6 limit check)
        if ($request->hasFile('new_gallery_images')) {
            if (!file_exists(public_path('uploads/gallery'))) {
                mkdir(public_path('uploads/gallery'), 0755, true);
            }

            foreach ($request->file('new_gallery_images') as $file) {
                if (count($currentGallery) >= 6) {
                    break; // Cap photo reel size natively
                }

                $filename = 'work_' . $user->company_id . '_' . Str::random(8) . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/gallery'), $filename);
                $currentGallery[] = 'uploads/gallery/' . $filename;
            }
        }

        // 3. Persist flat updates to database
        DB::table($companyTable)
            ->where('id', $user->company_id)
            ->update([
                'name'                  => $validated['name'],
                'company_bio'           => $validated['company_bio'],
                'work_philosophy'       => $validated['work_philosophy'],
                'years_in_business'     => $validated['years_in_business'],
                'license_number'        => $validated['license_number'],
                'insurance_badge'       => $request->has('insurance_badge') ? 1 : 0,
                'typical_response_time' => $validated['typical_response_time'],
                'warranty_details'      => $validated['warranty_details'],
                'gallery_paths'         => json_encode($currentGallery),
                'updated_at'            => now(),
            ]);

        Log::info("👑 Trust Engine Profile updated dynamically for Company ID: {$user->company_id}");

        return redirect()->route('workspace.profile.edit')->with('status', '⚡ Brand reputation metrics and trust signals updated successfully.');
    }

    /**
     * Display the high-conversion public homeowner profile preview page.
     */
    public function show($slug)
    {
        $userTable = (new User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';
        $companyTable = $prefix . 'companies';

        // Run self-healing schema patcher recursively on execution loops
        $this->ensureSchemaIsHealed($companyTable);

        $company = DB::table($companyTable)->where('slug', $slug)->first();

        if (!$company) {
            abort(404, 'Contractor profile workspace not found.');
        }

        $galleryImages = !empty($company->gallery_paths) ? json_decode($company->gallery_paths, true) : [];

        return view('brand.show', compact('company', 'galleryImages'));
    }

    /**
     * Runtime Plugin-Driven Database Self-Healing Structural Guard.
     */
    private function ensureSchemaIsHealed(string $tableName): void
    {
        if (Schema::hasTable($tableName)) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'company_bio')) {
                    $table->text('company_bio')->nullable();
                }
                if (!Schema::hasColumn($tableName, 'work_philosophy')) {
                    $table->text('work_philosophy')->nullable();
                }
                if (!Schema::hasColumn($tableName, 'years_in_business')) {
                    $table->integer('years_in_business')->nullable()->default(0);
                }
                if (!Schema::hasColumn($tableName, 'license_number')) {
                    $table->string('license_number', 100)->nullable();
                }
                if (!Schema::hasColumn($tableName, 'insurance_badge')) {
                    $table->boolean('insurance_badge')->default(0);
                }
                if (!Schema::hasColumn($tableName, 'typical_response_time')) {
                    $table->string('typical_response_time', 100)->default('within 24 hours');
                }
                if (!Schema::hasColumn($tableName, 'warranty_details')) {
                    $table->string('warranty_details', 255)->nullable();
                }
                if (!Schema::hasColumn($tableName, 'gallery_paths')) {
                    $table->text('gallery_paths')->nullable();
                }
            });
        }
    }
}
