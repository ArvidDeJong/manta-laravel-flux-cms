<?php

namespace Manta\FluxCMS\Services;

use OpenAI; // via openai-php/client
use Illuminate\Support\Facades\Log;

class MantaOpenai
{
    protected $client;

    public function __construct()
    {
        $this->client = OpenAI::client(env('OPENAI_API_KEY'));
    }

    /**
     * Genereer content voor de opgegeven velden.
     *
     * @param  string $prompt  Globale omschrijving
     * @param  array  $fields  ['title' => 'omschrijving', 'body' => 'omschrijving', ...]
     * @return array
     */
    public function generate(string $prompt, array $fields): array
    {
        $messages = [
            ['role' => 'system', 'content' => 'Je bent een hulpvaardige Laravel AI assistant.'],
            ['role' => 'user', 'content' => $this->buildInstruction($prompt, $fields)],
        ];

        try {
            $result = $this->client->chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => $messages,
                'response_format' => [
                    'type' => 'json_schema',
                    'json_schema' => [
                        'name' => 'fields_schema',
                        'schema' => [
                            'type' => 'object',
                            'properties' => collect($fields)->map(fn($desc) => [
                                'type' => 'string',
                                'description' => $desc,
                            ])->toArray(),
                            'required' => array_keys($fields),
                        ],
                    ],
                ],
            ]);

            $content = $result['choices'][0]['message']['content'] ?? '{}';

            return json_decode($content, true) ?: [];
        } catch (\Throwable $e) {
            Log::error('OpenAI generate error: ' . $e->getMessage());
            return [];
        }
    }

    protected function buildInstruction(string $prompt, array $fields): string
    {
        $text = "Genereer op basis van de volgende omschrijving:\n\n";
        $text .= $prompt . "\n\n";
        $text .= "Vul de volgende velden in als JSON:\n";
        foreach ($fields as $key => $desc) {
            $text .= "- {$key}: {$desc}\n";
        }
        return $text;
    }
}
