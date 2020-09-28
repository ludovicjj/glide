<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class HomeController
{
    /** @var Environment $twig */
    private $twig;

    public function __construct
    (
        Environment $twig
    )
    {
        $this->twig = $twig;
    }

    /**
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     *
     * @Route("/", name="home")
     */
    public function index()
    {
        $content = 'Hello world';

        return new Response(
            $this->twig->render(
                'app/home.html.twig',
                [
                    'content' => $content
                ]
            )
        );
    }
}