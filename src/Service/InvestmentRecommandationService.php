<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class InvestmentRecommendationService
{
    private $httpClient;
    private $openAiApiKey;

    public function __construct(HttpClientInterface $httpClient, string $openAiApiKey)
    {
        $this->httpClient = $httpClient;
        $this->openAiApiKey = $openAiApiKey;
    }

    public function getRecommendations(): array
{
    $prompt = "Je veux des recommandations d'investissement basées sur les tendances générales du marché boursier. 
    Donne-moi trois actions populaires avec leurs tendances récentes (hausse, stable, baisse), en utilisant des informations 
    historiques et des secteurs en croissance, sans inclure de données en temps réel. Exprime-les sous forme de liste concise.";

    try {
        $response = $this->httpClient->request('POST', 'https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->openAiApiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'gpt-3.5-turbo', // ou gpt-3.5-turbo
                'messages' => [
                    ['role' => 'system', 'content' => 'Tu es un assistant financier qui propose des recommandations d’actions en bourse.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'max_tokens' => 100,
                'temperature' => 0.5,
            ],
        ]);

        $content = $response->toArray();

        // Vérifie si la réponse contient bien les choix attendus
        if (isset($content['choices'][0]['message']['content'])) {
            $recommendations = $content['choices'][0]['message']['content'];

            // Si la réponse semble être coupée ou tronquée (par exemple, elle contient juste une partie de la tendance), tu peux essayer d'ignorer les parties non complètes.
            // Utilisation d'une expression régulière pour nettoyer et rendre la réponse plus structurée
            $filteredRecommendations = preg_replace('/Tendance.*?hausse/', 'Tendance: hausse', $recommendations);

            // Diviser les recommandations en un tableau et filtrer les lignes vides
            return array_filter(array_map('trim', explode("\n", $filteredRecommendations)));
        }

        // Retourne un tableau vide si la réponse n'est pas valide
        return [];
    } catch (\Exception $e) {
        // Enregistre l'erreur pour le débogage
        error_log('Erreur lors de l’appel à l’API OpenAI : ' . $e->getMessage());
        return [];
    }
}
}