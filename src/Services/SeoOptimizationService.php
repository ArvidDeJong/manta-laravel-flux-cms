<?php

namespace Manta\FluxCMS\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use OpenAI\Laravel\Facades\OpenAI;

class SeoOptimizationService
{
    public function __construct(
        private readonly string $model = 'gpt-4o-mini'
    ) {}

    /**
     * Genereer SEO-geoptimaliseerde content voor routes.
     *
     * @param  array{
     *   subject:string,            // onderwerp van de pagina
     *   description:string,        // beschrijving van de pagina content
     *   route?:string|null,        // route naam voor context
     *   lang?:string|null,         // "nl" of "en"
     *   max_title_length?:int,     // max karakters voor SEO titel
     *   max_description_length?:int // max karakters voor SEO beschrijving
     * } $opts
     * @return array{
     *   seo_title:string,
     *   seo_description:string,
     *   title:string,
     *   keywords:array
     * }
     */
    public function generateSeoContent(array $opts): array
    {
        $opts = array_merge([
            'route' => null,
            'lang' => 'nl',
            'max_title_length' => 60,
            'max_description_length' => 160,
        ], $opts);

        $system = <<<SYS
Je bent een Nederlandse SEO-specialist. Maak SEO-geoptimaliseerde content die goed rankt in Google.
Lever UITSLUITEND JSON, geen uitleg, geen markdown.
JSON keys: seo_title, seo_description, title, keywords.

- seo_title: max {$opts['max_title_length']} karakters, bevat hoofdkeyword, pakkend en klikbaar
- seo_description: max {$opts['max_description_length']} karakters, bevat call-to-action, beschrijft waarde voor gebruiker
- title: korte, duidelijke paginatitel voor in CMS
- keywords: array van 5-8 relevante zoektermen

Richtlijnen:
- Gebruik natuurlijke Nederlandse taal
- Vermijd keyword stuffing
- Maak titles en descriptions uniek en waardevol
- Focus op zoekintentie van gebruikers
- Gebruik actieve taal in descriptions

Schrijf in taalcode "{$opts['lang']}".
SYS;

        $user = [
            'subject' => $opts['subject'],
            'description' => $opts['description'],
            'route' => $opts['route'],
        ];

        $response = OpenAI::chat()->create([
            'model' => $this->model,
            'messages' => [
                ['role' => 'system', 'content' => $system],
                ['role' => 'user', 'content' => json_encode($user, JSON_UNESCAPED_UNICODE)],
            ],
            'temperature' => 0.7,
        ]);

        $raw = $response->choices[0]->message->content ?? '{}';

        // Robuust JSON parsen
        $data = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);

        // Validatie
        $validated = Validator::validate($data, [
            'seo_title' => ['required', 'string', 'max:' . ($opts['max_title_length'] + 10)],
            'seo_description' => ['required', 'string', 'max:' . ($opts['max_description_length'] + 20)],
            'title' => ['required', 'string', 'max:100'],
            'keywords' => ['required', 'array', 'min:3', 'max:10'],
            'keywords.*' => ['string', 'max:50'],
        ]);

        // Trim tot exacte lengtes
        $validated['seo_title'] = $this->trimToLength($validated['seo_title'], $opts['max_title_length']);
        $validated['seo_description'] = $this->trimToLength($validated['seo_description'], $opts['max_description_length']);

        return Arr::only($validated, ['seo_title', 'seo_description', 'title', 'keywords']);
    }

    /**
     * Trim tekst tot specifieke lengte zonder woorden af te breken.
     */
    private function trimToLength(string $text, int $maxLength): string
    {
        if (strlen($text) <= $maxLength) {
            return $text;
        }

        $trimmed = substr($text, 0, $maxLength);
        $lastSpace = strrpos($trimmed, ' ');
        
        if ($lastSpace !== false && $lastSpace > $maxLength * 0.8) {
            return substr($trimmed, 0, $lastSpace);
        }

        return $trimmed;
    }

    /**
     * Analyseer bestaande SEO content en geef verbetervoorstellen.
     */
    public function analyzeSeoContent(string $title, string $description, string $content = ''): array
    {
        $analysis = [
            'score' => 0,
            'issues' => [],
            'suggestions' => [],
        ];

        // Title analyse
        $titleLength = strlen($title);
        if ($titleLength < 30) {
            $analysis['issues'][] = 'SEO titel is te kort (< 30 karakters)';
            $analysis['suggestions'][] = 'Maak de titel uitgebreider met relevante keywords';
        } elseif ($titleLength > 60) {
            $analysis['issues'][] = 'SEO titel is te lang (> 60 karakters)';
            $analysis['suggestions'][] = 'Kort de titel in om afkapping in zoekresultaten te voorkomen';
        } else {
            $analysis['score'] += 25;
        }

        // Description analyse
        $descLength = strlen($description);
        if ($descLength < 120) {
            $analysis['issues'][] = 'SEO beschrijving is te kort (< 120 karakters)';
            $analysis['suggestions'][] = 'Voeg meer waardevolle informatie toe aan de beschrijving';
        } elseif ($descLength > 160) {
            $analysis['issues'][] = 'SEO beschrijving is te lang (> 160 karakters)';
            $analysis['suggestions'][] = 'Kort de beschrijving in om volledig zichtbaar te zijn in Google';
        } else {
            $analysis['score'] += 25;
        }

        // Keyword analyse
        $titleWords = str_word_count(strtolower($title), 1, 'àáâãäåæçèéêëìíîïðñòóôõöøùúûüýþÿ');
        $descWords = str_word_count(strtolower($description), 1, 'àáâãäåæçèéêëìíîïðñòóôõöøùúûüýþÿ');
        
        $commonWords = array_intersect($titleWords, $descWords);
        if (count($commonWords) > 0) {
            $analysis['score'] += 25;
        } else {
            $analysis['issues'][] = 'Geen gemeenschappelijke keywords tussen titel en beschrijving';
            $analysis['suggestions'][] = 'Gebruik consistente keywords in zowel titel als beschrijving';
        }

        // Call-to-action check
        $ctaWords = ['ontdek', 'lees', 'bekijk', 'download', 'krijg', 'vind', 'leer'];
        $hasCtA = false;
        foreach ($ctaWords as $cta) {
            if (stripos($description, $cta) !== false) {
                $hasCtA = true;
                break;
            }
        }
        
        if ($hasCtA) {
            $analysis['score'] += 25;
        } else {
            $analysis['suggestions'][] = 'Voeg een call-to-action toe aan de beschrijving';
        }

        return $analysis;
    }
}
