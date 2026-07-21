<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat');
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        $apiKey = config('services.gemini.api_key');
        $baseUrl = rtrim((string) config('services.gemini.base_url'), '/');
        $model = config('services.gemini.model');
        $fallbackModels = config('services.gemini.fallback_models', []);
        $verifySsl = filter_var(config('services.gemini.verify_ssl', true), FILTER_VALIDATE_BOOL);

        if (! $apiKey) {
            return response()->json([
                'reply' => 'Gemini API key is missing.',
                'error' => 'missing_api_key',
            ], 500);
        }

        $modelsToTry = array_values(array_unique(array_filter([
            $model,
            ...$fallbackModels,
        ])));

        $response = null;
        $data = null;

        foreach ($modelsToTry as $candidateModel) {
            $url = $baseUrl.'/models/'.$candidateModel.':generateContent?key='.$apiKey;

            try {
                $response = Http::timeout(30)
                    ->acceptJson()
                    ->withOptions(['verify' => $verifySsl])
                    ->post($url, [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => trim($validated['message'])],
                                ],
                            ],
                        ],
                    ]);
            } catch (ConnectionException $exception) {
                report($exception);

                return response()->json([
                    'reply' => 'Unable to connect to AI service. Please try again shortly.',
                    'error' => 'connection_failed',
                ], 503);
            }

            $data = $response->json();

            if ($response->successful()) {
                break;
            }

            $errorMessage = (string) ($data['error']['message'] ?? '');

            if (! str_contains(strtolower($errorMessage), 'not found')) {
                break;
            }
        }

        if (! $response || ! $response->successful()) {
            return response()->json([
                'reply' => $data['error']['message'] ?? 'AI service error.',
                'error' => 'api_error',
                'debug' => $data,
            ], 502);
        }

        $reply =
            $data['candidates'][0]['content']['parts'][0]['text']
            ?? ($data['error']['message'] ?? 'No response from AI');

        return response()->json([
            'reply' => $reply,
        ]);
    }
}
