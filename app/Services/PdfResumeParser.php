<?php

namespace App\Services;

use App\Services\Contracts\ResumeParserInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class PdfResumeParser implements ResumeParserInterface
{
    /**
     * Extract plain text from PDF.
     */
    public function extractText(string $absoluteFilePath): string
    {
        if (!file_exists($absoluteFilePath)) {
            throw new Exception("Resume file does not exist at path: {$absoluteFilePath}");
        }

        // Try using smalot/pdfparser if vendor package is loaded
        if (class_exists(\Smalot\PdfParser\Parser::class)) {
            try {
                $parser = new \Smalot\PdfParser\Parser();
                $pdf = $parser->parseFile($absoluteFilePath);
                $text = $pdf->getText();
                if (!empty(trim($text))) {
                    return $text;
                }
            } catch (Exception $e) {
                Log::warning("Smalot PDF parser exception: " . $e->getMessage());
            }
        }

        // Fallback: Read raw content and extract readable ASCII/UTF-8 streams
        $content = file_get_contents($absoluteFilePath);
        return $this->fallbackStreamTextExtraction($content);
    }

    /**
     * Fallback stream extraction for standard uncompressed / lightly compressed text streams in PDF.
     */
    protected function fallbackStreamTextExtraction(string $content): string
    {
        // Extract text streams between BT and ET operators or uncompressed text
        $text = '';
        if (preg_match_all('/BT[\s\S]*?ET/i', $content, $matches)) {
            foreach ($matches[0] as $block) {
                // Extract strings in parentheses e.g. (John Doe) Tj
                if (preg_match_all('/\((.*?)\)\s*T[jJ]/', $block, $strMatches)) {
                    $text .= implode(' ', $strMatches[1]) . "\n";
                }
            }
        }

        if (empty(trim($text))) {
            // Filter non-binary readable character sequences
            $sanitized = preg_replace('/[^\x20-\x7E\t\r\n]/', ' ', $content);
            $lines = explode("\n", $sanitized);
            $meaningful = array_filter($lines, fn($line) => strlen(trim($line)) > 3 && !preg_match('/^(obj|endobj|stream|endstream|xref|trailer)/i', trim($line)));
            $text = implode("\n", array_slice($meaningful, 0, 100));
        }

        return trim($text);
    }
}
