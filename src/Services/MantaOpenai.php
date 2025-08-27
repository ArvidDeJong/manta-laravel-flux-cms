<?php

namespace Manta\FluxCMS\Services;

use OpenAI;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Manta\FluxCMS\Models\Upload;

class MantaOpenai
{
    protected $client;

    public function __construct()
    {
        $this->client = OpenAI::client(env('OPENAI_API_KEY'));
    }

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
            Log::error('MantaOpenai generate error: ' . $e->getMessage());
            return [];
        }
    }

    public function generateImage(string $prompt, string $model, int|string $model_id, string $size = '1024x1024'): ?string
    {

        try {
            $result = $this->client->images()->create([
                'model' => 'dall-e-3',
                'prompt' => $prompt,
                'n' => 1,
                'size' => $size,
                'response_format' => 'url',
            ]);

            $imageUrl = $result->data[0]->url ?? null;

            if (!$imageUrl) {
                return null;
            }

            // Download afbeelding van OpenAI
            $imageContent = Http::timeout(30)->get($imageUrl)->body();


            // // Genereer unieke bestandsnaam
            // $filename = 'ai-' . now()->format('Ymd-His') . '-' . Str::random(8) . '.png';
            // $path = 'ai/images/' . $filename;

            // // Sla afbeelding op in storage/app/public
            // Storage::disk('public')->put($path, $imageContent);
            // dd($imageContent);

            $upload = new Upload();
            $result = $upload->upload($imageContent, $model,  $model_id, ['disk' => 'public', 'filename' => 'ai-' . now()->format('Ymd-His') . '-' . Str::random(8) . '.png']);

            // Return publieke URL naar lokaal opgeslagen bestand
            return  $result->id;
        } catch (\Throwable $e) {
            Log::error('MantaOpenai generateImage error: ' . $e->getMessage());
            return null;
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
