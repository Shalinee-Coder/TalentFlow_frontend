<?php

namespace App\Services\Contracts;

interface ResumeParserInterface
{
    /**
     * Extract plain text from the given absolute PDF file path.
     */
    public function extractText(string $absoluteFilePath): string;
}
