<?php


namespace App\Controller;



use App\Form\Type\TaskType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CreateController
{
    /** @var FormFactoryInterface $formFactory */
    private $formFactory;

    /** @var Environment $twig */
    private $twig;


    public function __construct(
        FormFactoryInterface $formFactory,
        Environment $twig
    )
    {
        $this->formFactory = $formFactory;
        $this->twig = $twig;
    }

    /**
     * @param Request $request

     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     *
     * @return Response
     *
     * @Route("/create", name="create_game")
     *
     */
    public function create(Request $request): Response
    {

        $form = $this->formFactory->create(TaskType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $errors = $form->getErrors(true);
            dd($errors);
        }

        return new Response(
            $this->twig->render('app/create.html.twig', ['form' => $form->createView()])
        );
    }
}