<?php declare(strict_types=1);

namespace Symfony4\View;

use Symfony4\Form\RegisterType;
use Seat\Domain\Client\UseCase\Register\RegisterRequest;
use Seat\Presentation\Client\RegisterHtmlViewModel;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class RegisterView
{
    private $twig;
    private $formFactory;
    private $router;

    public function __construct(Environment $twig, FormFactoryInterface $formFactory, RouterInterface $router)
    {
        $this->twig = $twig;
        $this->formFactory = $formFactory;
        $this->router = $router;
    }

    /**
     * @throws
     */
    public function generateView(RegisterRequest $registerRequest, RegisterHtmlViewModel $viewModel): Response
    {
        if ($viewModel->clientSaved) {
            return new RedirectResponse($this->router->generate('register-complete'));
        }

        $form = $this->formFactory->createBuilder(RegisterType::class, $registerRequest)->getForm();

        return new Response($this->twig->render('page/register.html.twig', ['form' => $form->createView(), 'viewModel' => $viewModel]));
    }
}
