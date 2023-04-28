<?php declare(strict_types=1);

namespace App\Controllers;

use App\Services\ApiClient;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class GifController
{
    private ApiClient $apiClient;
    private Environment $twig;
    private FilesystemLoader $twigLoader;

    public function __construct()
    {
        $this->apiClient = new ApiClient();
        $this->twigLoader = new FilesystemLoader('app/View');
        $this->twig = new Environment($this->twigLoader);
    }

    public function trending(): string
    {
        $gifs = $this->apiClient->fetchTrending();
        return $this->twig->render('GifCollectionView.twig', ['gifs' => $gifs]);
    }

    public function searched(): string
    {
        if (empty($_POST['search'])) {
            header('Location: /error/Fix your keyboard');
            exit;
        }
        $gifs = $this->apiClient->fetchSearched($_POST['search']);
        if (empty($gifs)) {
            header('Location: /error/No results found for ' . $_POST['search']);
            exit;
        };
        return $this->twig->render('GifCollectionView.twig', ['gifs' => $gifs]);
    }
    public function random(): string
    {
        $gif = $this->apiClient->fetchRandom();
        return $this->twig->render('RandomGifView.twig', ['gif' => $gif]);
    }
}