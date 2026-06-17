<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;

class TwilioService
{
    protected $sid;
    protected $token;
    protected $from;

    /**
     * Initialize the SMS dispatcher with secure environmental safeguards.
     */
    public function __construct()
    {
        $this->sid = config('services.twilio.sid') ?? env('TWILIO_SID');
        $this->token = config('services.twilio.token') ?? env('TWILIO_AUTH_TOKEN');
        $this->from = config('services.twilio.from') ?? env('TWILIO_FROM_NUMBER');
    }

    /**
     * Dispatch an outbound notification text loop safely.
     */
    public function sendSms(string $to, string $message): bool
    {
        // Log the transmission attempt for field debugging audits
        Log::info("📨 Twilio outbound dispatch queued for destination: {$to}");

        // Safeguard: Prevent crashes if credentials aren't set in the active .env yet
        if (!$this->sid || !$this->token || !$this->from) {
            Log::warning("⚠️ Twilio execution bypassed. Missing environmental configuration keys.");
            return false;
        }

        try {
            // Placeholder for the upcoming Twilio SDK REST client execution payload
            // $client = new \Twilio\Rest\Client($this->sid, $this->token);
            // $client->messages->create($to, ['from' => $this->from, 'body' => $message]);

            return true;
        } catch (Exception $e) {
            Log::error("❌ Twilio dispatch transmission failure: " . $e->getMessage());
            return false;
        }
    }
}
