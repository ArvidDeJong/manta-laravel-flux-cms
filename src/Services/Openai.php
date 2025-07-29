<?php

namespace Manta\FluxCMS\Services;

class Openai
{
    public string $sourceLanguage = 'nl';
    public string $destinationLanguage = 'en';
    public ?string $question = null;

    public function call_api($fields)
    {

        $curl = curl_init();
        // Vervang in call_api method de authorization header als volgt:
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.openai.com/v1/chat/completions',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($fields),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . env('OPENAI_API_KEY')
            ),
        ));

        curl_close($curl);

        return json_decode(curl_exec($curl), true);
    }

    public function translate()
    {
        if ($this->question == null) {
            return ['error' => 'No question entered'];
        }
        $fields = ["model" => "gpt-4", "temperature" => 0, "max_tokens" => 256];
        $fields['messages'] = [
            ["role" => "system", "content" => "You will be provided with a sentence in ISO language {$this->sourceLanguage}, and your task is to translate it into ISO language {$this->destinationLanguage}."],
            ["role" => "user", "content" => $this->question]
        ];
        $response = $this->call_api($fields);
        return ['answer' => $response['choices'][0]['message']['content']];
    }

    public function getSeoTitle()
    {
        if ($this->question == null) {
            return ['error' => 'No question entered'];
        }
        $fields = ["model" => "gpt-4", "temperature" => 0, "max_tokens" => 256];
        $fields['messages'] = [
            ["role" => "system", "content" => "Je krijgt een zin in ISO taal {$this->sourceLanguage} van 'ID oiltools uit Nederland', en je taak is om een SEO title te maken"],
            ["role" => "user", "content" => $this->question]
        ];
        $response = $this->call_api($fields);
        return ['answer' => $response['choices'][0]['message']['content']];
    }

    public function getSeoDescription()
    {
        if ($this->question == null) {
            return ['error' => 'No question entered'];
        }
        $fields = ["model" => "gpt-4", "temperature" => 0, "max_tokens" => 256];
        $fields['messages'] = [
            ["role" => "system", "content" => "Je krijgt een zin in ISO taal {$this->sourceLanguage} van 'ID oiltools uit Nederland', en je taak is om een SEO description te maken"],
            ["role" => "user", "content" => $this->question]
        ];
        $response = $this->call_api($fields);
        return ['answer' => $response['choices'][0]['message']['content']];
    }

    public function generateNews($subject, $description)
    {
        if (!$subject || !$description) {
            return ['error' => 'Subject or description is missing'];
        }

        // Velden voorbereiden
        $fields = [
            "model" => "gpt-4-0613", // Gebruik een model dat function-calling ondersteunt
            "temperature" => 0.7,
            "messages" => [
                [
                    "role" => "system",
                    "content" => "Je bent een API die nieuwsberichten genereert. Het nieuwsbericht moet voldoen aan een gestructureerd JSON-formaat."
                ],
                [
                    "role" => "user",
                    "content" => "Schrijf een nieuwsbericht over '{$subject}' gebaseerd op: '{$description}'."
                ]
            ],
            "functions" => [
                [
                    "name" => "generate_news",
                    "description" => "Genereert een nieuwsbericht",
                    "parameters" => [
                        "type" => "object",
                        "properties" => [
                            "title" => ["type" => "string", "description" => "De titel van het nieuwsbericht"],
                            "title_2" => ["type" => "string", "description" => "De subtitel van het nieuwsbericht"],
                            "excerpt" => ["type" => "string", "description" => "Een korte beschrijving"],
                            "content" => ["type" => "string", "description" => "Het volledige nieuwsbericht"]
                        ],
                        "required" => ["hoofdtitel", "subtitel", "omschrijving", "bericht"]
                    ]
                ]
            ],
            "function_call" => ["name" => "generate_news"]
        ];

        $response = $this->call_api($fields);

        // Valideer of een function_call is uitgevoerd
        if (isset($response['choices'][0]['message']['function_call']['arguments'])) {
            $arguments = json_decode($response['choices'][0]['message']['function_call']['arguments'], true);

            if (json_last_error() === JSON_ERROR_NONE) {
                return $arguments; // Gestructureerde JSON-output
            } else {
                return ['error' => 'Invalid JSON format in function call'];
            }
        }

        return ['error' => 'Geen geldige function call ontvangen van OpenAI'];
    }
}
