<?php declare(strict_types=1);

namespace Symfony4\Controller;

use Symfony4\Security\User;
use Symfony4\View\UpdateClientView;
use Seat\Domain\Client\UseCase\GetClient\GetClient;
use Seat\Domain\Client\UseCase\GetClient\GetClientRequest;
use Seat\Domain\Client\UseCase\UpdateClient\UpdateClient;
use Seat\Domain\Client\UseCase\UpdateClient\UpdateClientRequest;
use Seat\Presentation\Client\EditableClient;
use Seat\Presentation\Client\GetClientHtmlPresenter;
use Seat\Presentation\Client\UpdateClientHtmlPresenter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/mes-informations", name="user.my-info")
 * @Security("is_granted('IS_AUTHENTICATED_REMEMBERED')")
 * @ParamConverter("nullableRequest", options={"form":"Symfony4\Form\UpdateClientType"})
 */
class UpdateClientController
{
    /** @param User $user */
    public function __invoke(
        UpdateClient $updateClient,
        UpdateClientView $view,
        UpdateClientHtmlPresenter $updatePresenter,

        GetClient $getClient,
        GetClientHtmlPresenter $getClientPresenter,

        ?EditableClient $nullableRequest,
        UserInterface $user = null
    ) {
        if ($nullableRequest === null) {
            $getClient->execute(new GetClientRequest($user->getId()), $getClientPresenter);

            return $view->generateViewBeforePost($getClientPresenter->viewModel());
        }

        $request = UpdateClientRequest::fromEditable($nullableRequest)->byClientId($user->getId());
        $updateClient->execute($request, $updatePresenter);

        return $view->generateViewAfterPost($request, $updatePresenter->viewModel());
    }
}
