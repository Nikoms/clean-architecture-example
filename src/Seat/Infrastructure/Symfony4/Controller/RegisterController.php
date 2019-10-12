<?php declare(strict_types=1);

namespace Symfony4\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony4\View\RegisterView;
use Seat\Domain\Client\UseCase\Register\Register;
use Seat\Domain\Client\UseCase\Register\RegisterRequest;
use Seat\Presentation\Client\RegisterHtmlPresenter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/register", name="register")
 * @ParamConverter("form2request", options={"form":"Symfony4\Form\RegisterType"})
 */
class RegisterController
{
    public function __invoke(Request $request, Register $registerUseCase, RegisterView $view, RegisterHtmlPresenter $presenter, RegisterRequest $form2request)
    {
        $registerRequest = new RegisterRequest();
        $registerRequest->email = $request->request->get('email');
        $registerRequest->password = $request->request->get('password');

        $registerUseCase->execute($registerRequest);
        //...

        return $view->generateView($form2request, $presenter->viewModel());
    }
}
