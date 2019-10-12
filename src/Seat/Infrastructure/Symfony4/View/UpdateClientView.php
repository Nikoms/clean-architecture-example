<?php declare(strict_types=1);

namespace Symfony4\View;

use Symfony4\Form\UpdateClientType;
use Seat\Domain\Client\UseCase\UpdateClient\UpdateClientRequest;
use Seat\Presentation\Client\EditableClient;
use Seat\Presentation\Client\GetClientHtmlViewModel;
use Seat\Presentation\Client\UpdateClientHtmlViewModel;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class UpdateClientView
{
    private $twig;
    private $formFactory;
    private $router;
    private $session;

    public function __construct(Environment $twig, FormFactoryInterface $formFactory, RouterInterface $router, Session $session)
    {
        $this->twig = $twig;
        $this->formFactory = $formFactory;
        $this->router = $router;
        $this->session = $session;
    }

    public function generateViewAfterPost(UpdateClientRequest $request, UpdateClientHtmlViewModel $viewModel)
    {
        if ($viewModel->notificationMessage && $viewModel->notificationType) {
            $this->session->getFlashBag()->add($viewModel->notificationType, $viewModel->notificationMessage);
        }
        if ($viewModel->redirectToLogout) {
            return new RedirectResponse($this->router->generate('logout'));
        }
        if ($viewModel->redirectToThankYouPage) {
            return new RedirectResponse($this->router->generate('user.my-info'));
        }

        return $this->generateView($request, $viewModel->errors);
    }

    public function generateViewBeforePost(GetClientHtmlViewModel $viewModel)
    {
        return $this->generateView($viewModel->editableClient());
    }

    private function generateForm(EditableClient $editableClient): FormInterface
    {
        return $this->formFactory->createBuilder(UpdateClientType::class, $editableClient)->getForm();
    }

    /** @throws */
    private function generateView(EditableClient $editableClient, $errors = []): Response
    {
        return new Response(
            $this->twig->render(
                'page/update-client.html.twig',
                [
                    'form' => $this->generateForm($editableClient)->createView(),
                    'errors' => $errors,
                ]
            )
        );
    }
}
