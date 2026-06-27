<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
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

        // Restore dynamic prefix resolution matching your custom user layout
        $userTable = (new User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';
        $companyTable = $prefix . 'companies';

        // Run self-healing schema patcher safely without dangerous structural modifications
        $this->ensureSchemaIsHealed($companyTable);

        $company = DB::table($companyTable)->where('id', $user->company_id)->first();

        // Harmonize legacy array structures with fresh onboarding profiles
        $galleryImages = $this->safeJsonDecode($company->gallery_paths ?? null);
        if (empty($galleryImages) && !empty($company->portfolio_image_path)) {
            $galleryImages[] = $company->portfolio_image_path;
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
            'name'                        => 'required|string|max:255',
            'logo'                        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'company_bio'                 => 'nullable|string|max:3000',
            'company_bio_long'            => 'nullable|string|max:3000',
            'company_bio_short'           => 'nullable|string|max:255',
            'work_philosophy'             => 'nullable|string|max:3000',
            'years_in_business'           => 'nullable|integer|min:0|max:100',
            'years_experience'            => 'nullable|integer|min:0|max:100',
            'license_number'              => 'nullable|string|max:100',
            'insurance_badge'             => 'nullable|boolean',
            'is_insured'                  => 'nullable|boolean',
            'typical_response_time'       => 'required|string|max:100',
            'warranty_details'            => 'nullable|string|max:255',
            'new_gallery_images.*'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'remove_images'               => 'nullable|array',

            // Directory Positioning Parameters
            'signature_specialty'         => 'nullable|string|max:255',
            'trade'                       => 'nullable|string|max:255',
            'target_service_cities'       => 'nullable|string|max:255',
            'competitive_advantage'       => 'nullable|string|max:100',
            'ideal_client_vibe'           => 'nullable|string|max:100',
            'monetization_routing_phone'  => 'nullable|string|max:50',
        ]);

        $userTable = (new User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';
        $companyTable = $prefix . 'companies';

        $this->ensureSchemaIsHealed($companyTable);

        $company = DB::table($companyTable)->where('id', $user->company_id)->first();

        // Load the existing active paths securely using the unrolling filter
        $currentGallery = $this->safeJsonDecode($company->gallery_paths ?? null);
        $logoPath = $company->logo_path ?? null;

        // 1. Process Dedicated Corporate Logo Upload Stream
        if ($request->hasFile('logo')) {
            if (!file_exists(public_path('uploads/logos'))) {
                mkdir(public_path('uploads/logos'), 0755, true);
            }

            if (!empty($logoPath) && file_exists(public_path($logoPath))) {
                @unlink(public_path($logoPath));
            }

            $logoFile = $request->file('logo');
            $logoName = 'logo_' . $user->company_id . '_' . time() . '.' . $logoFile->getClientOriginalExtension();
            $logoFile->move(public_path('uploads/logos'), $logoName);
            $logoPath = 'uploads/logos/' . $logoName;
        }

        // 2. Process image removals if flagged by checkbox purge hooks
        if (!empty($validated['remove_images'])) {
            foreach ($validated['remove_images'] as $imageToRemove) {
                if (($key = array_search($imageToRemove, $currentGallery)) !== false) {
                    unset($currentGallery[$key]);
                    if (file_exists(public_path($imageToRemove))) {
                        @unlink(public_path($imageToRemove));
                    }
                }
            }
        }

        // 3. NATIVE MULTI-FILE FILEPICKER VALIDATION MATRIX
        if ($request->hasFile('new_gallery_images')) {
            if (!file_exists(public_path('uploads/gallery'))) {
                mkdir(public_path('uploads/gallery'), 0755, true);
            }

            foreach ($request->file('new_gallery_images') as $file) {
                if ($file instanceof UploadedFile && $file->isValid()) {
                    if (count($currentGallery) >= 6) {
                        break;
                    }

                    $filename = 'work_' . $user->company_id . '_' . Str::random(8) . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/gallery'), $filename);
                    $currentGallery[] = 'uploads/gallery/' . $filename;
                }
            }
        }

        // Re-index target array parameters clean to avoid associative index formatting errors
        $sanitizedGallery = array_values(array_filter($currentGallery));

        // Synchronize values between dual schema frameworks to lock data integrity
        $bioLong = $validated['company_bio_long'] ?? $validated['company_bio'] ?? '';
        $yearsExp = $validated['years_experience'] ?? $validated['years_in_business'] ?? 0;
        $tradeSpecialty = $validated['trade'] ?? $validated['signature_specialty'] ?? '';
        $insuranceStatus = $request->has('insurance_badge') || $request->has('is_insured') ? 1 : 0;

        // 4. Persist updates across both legacy metrics and fresh blueprint rows
        DB::table($companyTable)
            ->where('id', $user->company_id)
            ->update([
                'name'                        => $validated['name'],
                'logo_path'                   => $logoPath,
                'company_bio'                 => $bioLong,
                'company_bio_long'            => $bioLong,
                'company_bio_short'           => $validated['company_bio_short'] ?? substr($bioLong, 0, 180),
                'work_philosophy'             => $validated['work_philosophy'] ?? null,
                'years_in_business'           => $yearsExp,
                'years_experience'            => $yearsExp,
                'license_number'              => $validated['license_number'] ?? null,
                'insurance_badge'             => $insuranceStatus,
                'is_insured'                  => $insuranceStatus,
                'typical_response_time'       => $validated['typical_response_time'],
                'warranty_details'            => $validated['warranty_details'] ?? null,
                'gallery_paths'               => json_encode($sanitizedGallery, JSON_UNESCAPED_SLASHES),

                // Save Directory Layout Positioning Fields Permanently
                'signature_specialty'         => $tradeSpecialty,
                'trade'                       => $tradeSpecialty,
                'target_service_cities'       => $validated['target_service_cities'] ?? null,
                'competitive_advantage'       => $validated['competitive_advantage'] ?? null,
                'ideal_client_vibe'           => $validated['ideal_client_vibe'] ?? null,
                'monetization_routing_phone'  => $validated['monetization_routing_phone'] ?? null,
                'updated_at'                  => now(),
            ]);

        Log::info("👑 Trust Engine Profile updated dynamically for Company ID: {$user->company_id}");

        return redirect()->route('workspace.profile.edit')->with('status', '⚡ Brand reputation metrics and trust signals updated successfully.');
    }

    /**
     * 🧠 SECURE ENDPOINT: High-Volume Flagship Gemini 3.5 Flash Entry Hub
     */
    public function generateAiAssist(Request $request)
    {
        $apiKey = config('services.gemini.key');
        if (empty($apiKey)) {
            return response()->json(['error' => 'Gemini API operational token is missing inside cached configurations.'], 500);
        }

        $validated = $request->validate([
            'type'                  => 'required|in:bio,philosophy',
            'name'                  => 'required|string|max:255',
            'years_in_business'     => 'nullable|string|max:10',
            'license_number'        => 'nullable|string|max:100',
            'signature_specialty'   => 'nullable|string|max:255',
            'target_service_cities' => 'nullable|string|max:255',
            'competitive_advantage' => 'nullable|string|max:100',
            'ideal_client_vibe'     => 'nullable|string|max:100'
        ]);

        $name = $validated['name'];
        $years = !empty($validated['years_in_business']) ? $validated['years_in_business'] . ' years' : 'multiple years';
        $license = !empty($validated['license_number']) ? 'license tracking reference ' . $validated['license_number'] : 'fully legal, credentialed status';

        $specialty = !empty($validated['signature_specialty']) ? $validated['signature_specialty'] : 'premium general trade crafts';
        $regions = !empty($validated['target_service_cities']) ? 'proudly dispatching service crews across ' . $validated['target_service_cities'] : 'serving local properties';

        $advantages = [
            'owner_onsite' => 'maintaining a strict owner on-site policy to personally oversee every single framing and structural pass',
            'rapid_response' => 'enforcing a rigid 15-minute communication guarantee to ensure no property owner is left waiting for updates',
            'clean_site' => 'adhering to an immaculate zero-mess site policy, guaranteeing your residential footprint is left cleaner than we found it',
            'transparent_pricing' => 'delivering absolute upfront line-item pricing visibility to completely eliminate hidden fees and unexpected budget creep'
        ];
        $edgeText = data_get($advantages, $validated['competitive_advantage'], 'delivering premium structural craftsmanship guarantees');

        $vibes = [
            'premium_custom' => 'specializing heavily in custom architectural transformations and premium property masterworks',
            'dependable_remodel' => 'delivering dependable residential remodels, structural additions, and modern renovations',
            'fast_track_repairs' => 'executing rapid-turnaround home restorations and immediate structural specialty field repairs'
        ];
        $vibeText = data_get($vibes, $validated['ideal_client_vibe'], 'providing top-tier trade services');

        if ($validated['type'] === 'bio') {
            $systemInstruction = "You are an elite conversion copywriter for local contractor directories. Your job is to draft a comprehensive, trust-building company profile biography. You must generate a fluid, detailed narrative block containing exactly 4 to 5 descriptive sentences. Avoid short summaries or truncation.";

            $userPrompt = "Write a comprehensive contractor company biography for the business named '{$name}'. You must expand the text to thoroughly weave in every one of these details across multiple distinct sentences:\n"
                . "1. Specialty: They specialize in {$specialty}.\n"
                . "2. Location: They serve homeowners across {$regions}.\n"
                . "3. Experience: They bring over {$years} of hands-on field expertise.\n"
                . "4. Legitimacy: They are fully legal, {$license}.\n"
                . "5. Advantage: Their competitive signature edge relies on {$edgeText}.\n"
                . "6. Project Focus: They are widely recognized for {$vibeText}.\n\n"
                . "Elaborate fully on each criteria to create a dense, premium copy block for local consumers.";
        } else {
            $systemInstruction = "You are a premium brand reputation engineer for elite construction specialties. Your job is to draft a thorough customer value commitment pledge. You must generate a substantial paragraph containing 3 to 4 thorough sentences detailing field protection rules and craftsmanship pride.";

            $userPrompt = "Write a substantial customer promise statement paragraph from the perspective of '{$name}'. Weave these specific parameters into a fluid multi-sentence narrative block:\n"
                . "1. Experience: Backed by over {$years} of reliable local field presence.\n"
                . "2. Competitive Edge: They strictly follow their promise of {$edgeText}.\n"
                . "3. Service Standard: They ensure high-end results by {$vibeText}.\n\n"
                . "Elaborate thoroughly on their dedication to structural site protection, cleanliness, communication updates, and craftsmanship warranties.";
        }

        try {
            $response = Http::withHeaders([
                'x-goog-api-key' => $apiKey,
                'Content-Type'   => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash:generateContent", [
                'systemInstruction' => [
                    'parts' => [
                        ['text' => $systemInstruction]
                    ]
                ],
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $userPrompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 1.0,
                    'maxOutputTokens' => 2500,
                ]
            ]);

            $responseJson = $response->json();

            if ($response->failed()) {
                Log::error('Gemini API framework gateway rejection: ' . $response->body());
                return response()->json(['error' => 'API gateway rejected content streams. Response Status Code: ' . $response->status()], 502);
            }

            $suggestion = data_get($responseJson, 'candidates.0.content.parts.0.text');

            if (empty($suggestion)) {
                Log::error('Gemini structural extraction failure: ' . json_encode($responseJson));

                $finishReason = data_get($responseJson, 'candidates.0.finishReason');
                $blockReason = data_get($responseJson, 'promptFeedback.blockReason');

                if ($finishReason) {
                    return response()->json(['error' => "Google blocked generation pass. Finish Code: {$finishReason}."], 422);
                }
                if ($blockReason) {
                    return response()->json(['error' => "Prompt payload blocked by Google gateway. Reason: {$blockReason}."], 422);
                }

                return response()->json(['error' => 'Model endpoint returned an empty response object structure.'], 502);
            }

            $cleanSuggestion = trim(str_replace(['`', '""'], '', $suggestion));

            return response()->json(['suggestion' => $cleanSuggestion]);

        } catch (\Exception $exception) {
            Log::error('Gemini GA Gateway Connection Exception: ' . $exception->getMessage());
            return response()->json(['error' => 'Network framework failed to complete communication streams.'], 500);
        }
    }

    /**
     * Display the high-conversion public homeowner profile preview page.
     */
    public function show($slug)
    {
        $userTable = (new User())->getTable();
        $prefix = str_contains($userTable, '_') ? explode('_', $userTable)[0] . '_' : 'sc_';
        $companyTable = $prefix . 'companies';

        $this->ensureSchemaIsHealed($companyTable);

        $company = DB::table($companyTable)->where('slug', $slug)->first();

        if (!$company) {
            abort(404, 'Contractor profile workspace not found.');
        }

        // Hydrate visual arrays dynamically across old structure boundaries and new assets
        $galleryImages = $this->safeJsonDecode($company->gallery_paths ?? null);
        if (empty($galleryImages) && !empty($company->portfolio_image_path)) {
            $galleryImages[] = $company->portfolio_image_path;
        }

        // Build seamless server-side content fallbacks for programmatic SEO stability
        $company->computed_bio_long = $company->company_bio_long ?? $company->company_bio ?? 'Professional field services provider.';
        $company->computed_bio_short = $company->company_bio_short ?? substr($company->computed_bio_long, 0, 160);
        $company->computed_experience = $company->years_experience ?? $company->years_in_business ?? 0;
        $company->computed_trade = $company->trade ?? $company->primary_specialty ?? $company->signature_specialty ?? 'Construction Specialties Trade Partner';

        return view('brand.show', compact('company', 'galleryImages'));
    }

    /**
     * Recursive, Multi-Pass Safe JSON Array Serialization Decoder.
     */
    private function safeJsonDecode($value): array
    {
        if (empty($value)) {
            return [];
        }

        if (is_array($value)) {
            return array_values(array_filter($value));
        }

        $data = $value;
        while (is_string($data)) {
            $decoded = json_decode($data, true);
            if (json_last_error() !== JSON_ERROR_NONE || $decoded === $data) {
                if (str_contains($data, '["') || str_contains($data, '[\"')) {
                    $data = stripslashes($data);
                    $attempt = json_decode($data, true);
                    if (is_array($attempt)) {
                        return array_values(array_filter($attempt));
                    }
                }
                break;
            }
            $data = $decoded;
        }

        return is_array($data) ? array_values(array_filter($data)) : [];
    }

    /**
     * Safe Plugin-Driven Database Self-Healing Structural Guard.
     */
    private function ensureSchemaIsHealed(string $tableName): void
    {
        if (Schema::hasTable($tableName)) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'logo_path')) {
                    $table->string('logo_path', 255)->nullable()->after('name');
                }
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
                    $table->longText('gallery_paths')->nullable();
                }

                // Self-Healing Core Onboarding Positioning Schema Updates
                if (!Schema::hasColumn($tableName, 'signature_specialty')) {
                    $table->string('signature_specialty', 255)->nullable()->after('license_number');
                }
                if (!Schema::hasColumn($tableName, 'target_service_cities')) {
                    $table->string('target_service_cities', 255)->nullable()->after('signature_specialty');
                }
                if (!Schema::hasColumn($tableName, 'competitive_advantage')) {
                    $table->string('competitive_advantage', 100)->nullable()->after('target_service_cities');
                }
                if (!Schema::hasColumn($tableName, 'ideal_client_vibe')) {
                    $table->string('ideal_client_vibe', 100)->nullable()->after('competitive_advantage');
                }
                if (!Schema::hasColumn($tableName, 'monetization_routing_phone')) {
                    $table->string('monetization_routing_phone', 50)->nullable()->after('ideal_client_vibe');
                }
            });
        }
    }
}
