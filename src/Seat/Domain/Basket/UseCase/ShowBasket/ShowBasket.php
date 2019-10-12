<?php declare(strict_types=1);

namespace Seat\Domain\Basket\UseCase\ShowBasket;

use Seat\Domain\Basket\Entity\BasketRepository;

class ShowBasket
{
    private $basketRepository;

    public function __construct(BasketRepository $basketRepository)
    {

        $this->basketRepository = $basketRepository;
    }

    public function execute(ShowBasketRequest $request, ShowBasketPresenter $presenter)
    {
        $response = new ShowBasketResponse();

        $basket = $this->basketRepository->getUserBasket($request->userId);
        $response->setBasket($basket);

        $presenter->present($response);
    }
}
