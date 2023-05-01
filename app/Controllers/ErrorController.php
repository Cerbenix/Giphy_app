<?php declare(strict_types=1);

namespace App\Controllers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class ErrorController
{
    private FilesystemLoader $twigLoader;
    private Environment $twig;

    public function __construct()
    {
        $this->twigLoader = new FilesystemLoader('app/Views');
        $this->twig = new Environment($this->twigLoader);
    }

    public function show(array $vars): string
    {
        $message = $vars['message'];
        header('refresh:5 url=/');
        return $this->twig->render('MessageView.twig', ['message' => $message]);

    }

}
