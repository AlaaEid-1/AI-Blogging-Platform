<?php

namespace Tests\Feature;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ChatControllerTest extends TestCase
{
    public function test_chat_send_returns_reply_on_success(): void
    {
        Http::fake([
            '*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Hello from AI'],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->postJson('/chat/send', [
            'message' => 'Hello',
        ]);

        $response->assertOk();
        $response->assertJsonPath('reply', 'Hello from AI');
    }

    public function test_chat_send_returns_provider_error_message(): void
    {
        Http::fake([
            '*' => Http::response([
                'error' => ['message' => 'Invalid API key'],
            ], 401),
        ]);

        $response = $this->postJson('/chat/send', [
            'message' => 'Hello',
        ]);

        $response->assertStatus(502);
        $response->assertJsonPath('error', 'api_error');
        $response->assertJsonPath('reply', 'Invalid API key');
    }

    public function test_chat_send_handles_connection_exception(): void
    {
        Http::fake(function () {
            throw new ConnectionException('cURL error 60');
        });

        $response = $this->postJson('/chat/send', [
            'message' => 'Hello',
        ]);

        $response->assertStatus(503);
        $response->assertJsonPath('error', 'connection_failed');
    }

    public function test_chat_send_validates_message_input(): void
    {
        Http::fake();

        $response = $this->postJson('/chat/send', [
            'message' => '',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors(['message']);
    }
}
