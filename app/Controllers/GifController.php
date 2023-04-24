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
        if($_POST && !empty($_POST['search'])){
            $gifs = $this->apiClient->fetchSearched($_POST['search']);
        } else {
            $gifs = $this->apiClient->fetchTrending();
        }
        include 'app/View/GifView.html';
    }
}