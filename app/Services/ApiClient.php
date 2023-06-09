<?php declare(strict_types=1);

namespace App\Services;

use App\Models\Gif;
use GuzzleHttp\Client;

class ApiClient
{
    private Client $apiClient;

    public function __construct()
    {
        $this->apiClient = new Client([
            'base_uri' => 'https://api.giphy.com'
        ]);
    }

    public function fetchTrending(): array
    {
        $params = [
            'query' => [
                'limit' => 24,
                'api_key' => $_ENV['API_KEY']
            ]
        ];
        $response = $this->apiClient->get('v1/gifs/trending', $params);
        $report = json_decode($response->getBody()->getContents());
        return $this->collectGifs($report);
    }

    public function fetchSearched(string $term): array
    {
        $params = [
            'query' => [
                'q' => $term,
                'limit' => 24,
                'api_key' => $_ENV['API_KEY']
            ]
        ];
        $response = $this->apiClient->get('v1/gifs/search', $params);
        $report = json_decode($response->getBody()->getContents());
        return $this->collectGifs($report);
    }

    public function fetchRandom(): Gif
    {
        $params = [
            'query' => [
                'api_key' => $_ENV['API_KEY']
            ]
        ];
        $response = $this->apiClient->get('v1/gifs/random', $params);
        $report = json_decode($response->getBody()->getContents());
        return new Gif($report->data->title, $report->data->images->original->url);
    }

    private function collectGifs(\stdClass $report): array
    {
        $gifCollection = [];
        foreach ($report->data as $gif) {
            $gifCollection[] = new Gif($gif->title, $gif->images->fixed_width->url);
        }
        return $gifCollection;
    }
}
