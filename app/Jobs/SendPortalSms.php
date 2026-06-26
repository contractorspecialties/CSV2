<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendPortalSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the queue worker will attempt the job before failing.
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying a timed-out messaging request.
     */
    public $backoff = 15;

    /**
     * Create a new asynchronous messaging dispatch token instance.
     */
    public function __construct(
        protected string $to,
        protected string $message,
        protected ?string $from = null
    ) {}

    /**
     * Execute the external API payload handshake off the main web request thread.
     */
    public function handle(): void
    {
        $fromLine = $this->from ?? env('TELNYX_DEFAULT_FROM');
        $apiKey = env('TELNYX_API_KEY');

        if (empty($apiKey) || empty($fromLine)) {
            Log::error('🛑 Telnyx Queue Worker Aborted: API Key or Default From line configuration missing from environment array.');
            return;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
            ])->post('https://api.telnyx.com/v2/messages', [
                'from' => $fromLine,
                'to'   => $this->to,
                'text' => $this->message,
            ]);

            if ($response->failed()) {
                throw new \Exception('Telnyx Gateway returned error code status: ' . $response->status() . ' - Body: ' . $response->body());
            }

            Log::info("📱 Asynchronous Text Dispatched Successfully to: {$this->to}");

        } catch (\Exception $e) {
            Log::error("⚠️ Background SMS Dispatch Failure: " . $e->getMessage());

            // Release the job back into the database grid to retry after our backoff window expires
            $this->release($this->backoff);
        }
    }
}
