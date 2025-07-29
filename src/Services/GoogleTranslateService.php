<?php

namespace Manta\FluxCMS\Services;

use Google\Cloud\Translate\V2\TranslateClient;

class GoogleTranslateService
{
    protected $translate;

    public function __construct()
    {
        if (file_exists(base_path('auth-google-translate.json'))) {
            $this->translate = new TranslateClient([
                'keyFilePath' => base_path('auth-google-translate.json'),
            ]);
        }
    }

    /**
     * Vertaal een string naar een doeltaal.
     */
    public function translate(string $text, string $targetLanguage, string $sourceLanguage = 'en'): ?string
    {
        if (empty($text)) {
            return null;
        }

        $result = $this->translate->translate($text, [
            'source' => $sourceLanguage,
            'target' => $targetLanguage,
        ]);

        return $result['text'] ?? null;
    }

    /**
     * Vertaal meerdere velden tegelijk.
     */
    public function translateFields(array $fields, string $targetLanguage, string $sourceLanguage = 'en'): array
    {
        return array_map(function ($value) use ($targetLanguage, $sourceLanguage) {
            // Controleer of het veld een geldige string is
            if (!is_string($value) || trim($value) === '') {
                return $value; // Retourneer de originele waarde (of null) als het veld leeg is
            }

            return $this->translate($value, $targetLanguage, $sourceLanguage);
        }, $fields);
    }
}
