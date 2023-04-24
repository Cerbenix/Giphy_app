<?php declare(strict_types=1);

namespace App\Controllers;

use App\Services\ApiClient;

class GifController
{
    private ApiClient $apiClient;

    public function __construct()
    {
        $this->apiClient = new ApiClient();
    }

    public function run(): void
    {
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $this->handleSearch($_GET['search']);
            return;
        }
        $this->handleTrending();
    }

    private function handleTrending(): void
    {
        $gifs = $this->apiClient->fetchTrending();
        include 'app/View/GifView.html';
    }

    private function handleSearch(string $term): void
    {
        $gifs = $this->apiClient->fetchSearched($term);
        include 'app/View/GifView.html';
    }
}