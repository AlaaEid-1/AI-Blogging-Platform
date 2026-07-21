<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiController extends Controller
{
    public function generate(Request $request)
    {
        $request->validate([
            'prompt' => ['required', 'string', 'max:1000']
        ]);

        $apiKey = config('services.gemini.api_key');
        $model = config('services.gemini.model', 'gemini-2.5-flash');
        $baseUrl = rtrim((string) config('services.gemini.base_url', 'https://generativelanguage.googleapis.com/v1beta'), '/');
        $verifySsl = filter_var(config('services.gemini.verify_ssl', true), FILTER_VALIDATE_BOOL);

        if (!$apiKey) {
            return response()->json(['error' => 'Gemini API key is not configured.'], 500);
        }

        $url = $baseUrl . '/models/' . $model . ':generateContent?key=' . $apiKey;

        $systemInstruction = "You are an expert blog post writer and SEO specialist. Write a high-quality blog article based on the user's topic. You MUST respond in pure JSON format matching this exact schema:
{
  \"title\": \"Catchy SEO optimized title\",
  \"content\": \"Full article content with HTML formatting (headings, paragraphs, strong, etc) suitable for a rich text editor. Write a comprehensive article.\",
  \"excerpt\": \"A short 150-character meta description/excerpt\",
  \"tags\": [\"tag1\", \"tag2\", \"tag3\"]
}
Do not return any markdown formatting around the JSON block. Return ONLY valid JSON.";

        try {
            $response = Http::timeout(45)
                ->withOptions(['verify' => $verifySsl])
                ->post($url, [
                    'system_instruction' => [
                        'parts' => [['text' => $systemInstruction]]
                    ],
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [['text' => 'Topic: ' . $request->prompt]]
                        ]
                    ],
                    'generationConfig' => [
                        'responseMimeType' => 'application/json',
                    ]
                ]);

            if ($response->failed()) {
                $errorMsg = $response->json('error.message') ?? 'Unknown Gemini API Error';
                \Illuminate\Support\Facades\Log::error('Gemini API Error Response: ' . $response->body());
                return response()->json(['error' => 'AI Generation Failed: ' . $errorMsg], 502);
            }

            $data = $response->json();
            $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (!$text) {
                \Illuminate\Support\Facades\Log::error('Gemini API returned no text content: ' . $response->body());
                return response()->json(['error' => 'No content returned from AI.'], 500);
            }

            // Remove potential markdown wrappers if Gemini ignores responseMimeType
            $text = preg_replace('/```json\s*/', '', $text);
            $text = preg_replace('/```\s*$/', '', $text);
            $json = json_decode(trim($text), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                \Illuminate\Support\Facades\Log::error('Gemini invalid JSON: ' . json_last_error_msg() . ' Text: ' . $text);
                return response()->json(['error' => 'AI returned invalid JSON format.'], 500);
            }

            return response()->json($json);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gemini API Exception: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            report($e);
            return response()->json(['error' => 'Connection to AI service timed out or failed. Please check logs.'], 503);
        }
    }
}
