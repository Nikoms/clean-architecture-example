<?php declare(strict_types=1);

namespace SeatTest\Domain\Basket\UseCase\RemoveFromBasket;

use PHPUnit\Framework\TestCase;
use Seat\Domain\Basket\Model\BasketProduct;
use Seat\Domain\Basket\UseCase\RemoveFromBasket\RemoveFromBasket;
use Seat\Domain\Basket\UseCase\RemoveFromBasket\RemoveFromBasketPresenter;
use Seat\Domain\Basket\UseCase\RemoveFromBasket\RemoveFromBasketRequest;
use Seat\Domain\Basket\UseCase\RemoveFromBasket\RemoveFromBasketResponse;
use SeatTest\_Mock\Domain\Basket\Entity\InMemoryBasketRepository;

class RemoveFromBasketTest extends TestCase implements RemoveFromBasketPresenter
{

    private $basketRepository;
    private $userId = 'user-id';
    /** @var RemoveFromBasketResponse */
    private $response;

    public function test_it_deletes_a_line_from_the_basket()
    {
        $this->basketRepository = new InMemoryBasketRepository();
        $this->basketRepository->addToBasket($this->userId, new BasketProduct('basket-id', 1, ',', 2, null, [], ''));

        $removeFromBasket = new RemoveFromBasket($this->basketRepository);
        $removeFromBasket->execute(new RemoveFromBasketRequest($this->userId, 'basket-id'), $this);

        $basket = $this->basketRepository->getUserBasket($this->userId);

        $this->assertTrue($basket->isEmpty());
        $this->assertTrue($this->response->isDone());
    }

    public function present(RemoveFromBasketResponse $response): void
    {
        $this->response = $response;
    }
}
