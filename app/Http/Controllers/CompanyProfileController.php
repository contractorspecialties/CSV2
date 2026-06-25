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

        // Unpack portfolio paths cleanly using our anti-collision decoding filter
        $galleryImages = $this->safeJsonDecode($company->gallery_paths ?? null);

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
            'logo'                  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
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

        // 4. Persist flat updates using explicit unescaped slashing filters
        DB::table($companyTable)
            ->where('id', $user->company_id)
            ->update([
                'name'                  => $validated['name'],
                'logo_path'             => $logoPath,
                'company_bio'           => $validated['company_bio'],
                'work_philosophy'       => $validated['work_philosophy'],
                'years_in_business'     => $validated['years_in_business'],
                'license_number'        => $validated['license_number'],
                'insurance_badge'       => $request->has('insurance_badge') ? 1 : 0,
                'typical_response_time' => $validated['typical_response_time'],
                'warranty_details'      => $validated['warranty_details'],
                'gallery_paths'         => json_encode($sanitizedGallery, JSON_UNESCAPED_SLASHES),
                'updated_at'            => now(),
            ]);

        Log::info("👑 Trust Engine Profile updated dynamically for Company ID: {$user->company_id}");

        return redirect()->route('workspace.profile.edit')->with('status', '⚡ Brand reputation metrics and trust signals updated successfully.');
    }

    /**
     * 🧠 SECURE ENDPOINT: Production GA Tier Gemini Assist Engine Handshaker
     */
    public function generateAiAssist(Request $request)
    {
        $apiKey = config('services.gemini.key');
        if (empty($apiKey)) {
            return response()->json(['error' => 'Gemini API operational token is missing inside cached configurations.'], 500);
        }

        $validated = $request->validate([
            'type'              => 'required|in:bio,philosophy',
            'name'              => 'required|string|max:255',
            'years_in_business' => 'nullable|string|max:10',
            'license_number'    => 'nullable|string|max:100',
        ]);

        $name = $validated['name'];
        $years = !empty($validated['years_in_business']) ? $validated['years_in_business'] . ' years' : 'multiple years';
        $license = !empty($validated['license_number']) ? 'holding active trade license ' . $validated['license_number'] : 'fully legal and credentialed';

        if ($validated['type'] === 'bio') {
            $systemInstruction = "CORE ROLE: Expert consumer psychology copywriter for high-end local home service contractors. TASK: Draft a robust, trust-building contractor bio paragraph. ADJECTIVE BAN: Avoid low-value buzzwords like 'cutting-edge', 'synergy', or 'passionate'. STRUCTURE CONSTRAINT: Write exactly 3 to 5 clear, highly detailed sentences outlining localized project execution stability, trade competence, and owner protection focus.";
            $userPrompt = "Write a comprehensive 3-to-5 sentence contractor profile bio paragraph for the company '{$name}'. They have been operating active field service crews in this region for {$years} and are {$license}. Start directly with the business context description payload. Output only the clean plain-text paragraph with no extra conversational filler.";
        } else {
            $systemInstruction = "CORE ROLE: Premium brand reputation manager for elite construction and property improvement specialities. TASK: Draft a robust customer value philosophy guarantee promise paragraph. STRUCTURE CONSTRAINT: Write exactly 3 to 4 substantial, authoritative sentences detailing specific property protection standards, clean job site rules, absolute workmanship pride, and total invoicing transparency. Avoid single-line slogans.";
            $userPrompt = "Write a comprehensive 3-to-4 sentence customer pledge statement paragraph from the perspective of '{$name}'. Highlight their clear workmanship values backed by {$years} of field care stability. Start directly with the promise narrative block. Provide only the clean text raw paragraph.";
        }

        try {
            // 🛡️ Upgraded destination parameter routing maps explicitly to production-grade gemini-2.5-flash engines
            $response = Http::withHeaders([
                'x-goog-api-key' => $apiKey,
                'Content-Type'   => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => "System Directives & Rules:\n" . $systemInstruction . "\n\nContext Execution Request:\n" . $userPrompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.7,
                    'maxOutputTokens' => 500,
                ]
            ]);

            if ($response->failed()) {
                Log::error('Gemini API production-tier gateway rejection: ' . $response->body());
                return response()->json(['error' => 'API gateway rejected content streams. Response Status Code: ' . $response->status()], 502);
            }

            $responseJson = $response->json();
            $suggestion = data_get($responseJson, 'candidates.0.content.parts.0.text');

            if (empty($suggestion)) {
                Log::error('Gemini API production pass returned an unparsable body layout: ' . json_encode($responseJson));
                return response()->json(['error' => 'Model engine output an unparsable content body layout.'], 502);
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

        // Unpack portfolio paths cleanly using our anti-collision decoding filter
        $galleryImages = $this->safeJsonDecode($company->gallery_paths ?? null);

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
            });
        }
    }
}
