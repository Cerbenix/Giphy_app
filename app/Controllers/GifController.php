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
        $gifs = $this->apiClient->fetchTrending();
        if($_POST){
            $gifs = $this->apiClient->fetchSearched($_POST['search']);
        }
        include 'app/View/GifView.html';
    }
}