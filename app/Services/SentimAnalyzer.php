<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class SentimAnalyzer implements CanAnalyzeSentiment
{
    private PendingRequest $client;

    /**
     * Create an instance of SentimAnalyzer
     */
    public function __construct()
    {
        $this->client = $this->client();
    }

    /**
     * Analyze the text for positive, negative or neutral sentiments.
     */
    public function analyze(string $text): string|null
    {
        try {
            return (string) $this->attemptAnalysis($text);
        } catch (Exception $e) {
            report($e);

            return null;
        }
    }

    /**
     * Create an HTTP client.
     */
    private function client(): PendingRequest
    {
        return Http::baseUrl(config('services.sentim.url'))->timeout(20);
    }

    /**
     * Make call to API to analyze text.
     */
    private function attemptAnalysis(string $text): mixed
    {
        return $this->client->acceptJson()
            ->post('/api/v1', compact('text'))
            ->throw()
            ->json('result.type');
    }
}
