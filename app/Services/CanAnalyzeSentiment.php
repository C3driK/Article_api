<?php

namespace App\Services;

interface CanAnalyzeSentiment
{
    /**
     * Analyze the text for positive, negative or neutral sentiments.
     */
    public function analyze(string $text): string|null;
}
