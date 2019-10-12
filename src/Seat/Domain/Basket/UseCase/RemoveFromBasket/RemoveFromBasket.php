<?php declare(strict_types=1);

namespace Seat\Domain\Basket\UseCase\RemoveFromBasket;

use Seat\Domain\Basket\Entity\BasketRepository;

class RemoveFromBasket
{
    private $basketRepository;

    public function __construct(BasketRepository $basketRepository)
    {
        $this->basketRepository = $basketRepository;
    }

    public function execute(RemoveFromBasketRequest $request, RemoveFromBasketPresenter $presenter)
    {
        $response = new RemoveFromBasketResponse();
        $this->basketRepository->delete($request->basketId, $request->userId);
        $response->setIsDone(true);

        $presenter->present($response);
    }
}
