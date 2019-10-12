<?php declare(strict_types=1);

namespace SeatTest\Domain\Basket\UseCase\AddProductToBasket;


use PHPUnit\Framework\TestCase;
use Seat\Domain\Menu\Entity\ProductOption;
use Seat\Domain\Menu\Entity\ProductSupplement;
use Seat\Domain\Basket\Model\BasketProduct;
use Seat\Domain\Basket\UseCase\AddProductToBasket\AddProductToBasket;
use Seat\Domain\Basket\UseCase\AddProductToBasket\AddProductToBasketPresenter;
use Seat\Domain\Basket\UseCase\AddProductToBasket\AddProductToBasketRequest;
use Seat\Domain\Basket\UseCase\AddProductToBasket\AddProductToBasketResponse;
use Seat\SharedKernel\Error\Notification;
use SeatTest\_Mock\Domain\Basket\Entity\InMemoryBasketRepository;
use SeatTest\_Mock\Domain\Menu\Entity\InMemoryProductOptionRepository;
use SeatTest\_Mock\Domain\Menu\Entity\InMemoryProductRepository;
use SeatTest\_Mock\Domain\Menu\Entity\InMemoryProductSupplementRepository;
use SeatTest\Domain\Basket\Entity\ProductBuilder;

class AddProductToBasketTest extends TestCase implements AddProductToBasketPresenter
{
    private $productRepository;
    private $basketRepository;
    private $product1;
    private $userId;
    private $product2;
    private $addToBasket;
    private $productOptionRepository;
    private $productOption1;
    private $productOption2;
    private $productSupplement1a;
    private $productSupplementRepository;
    private $productSupplement2a;
    /** @var AddProductToBasketResponse */
    private $response;

    protected function setUp()
    {
        $this->product1 = ProductBuilder::aProduct()->build();
        $this->product2 = ProductBuilder::aProduct()->categoryId('category-2')->id('product-2')->name('product2')->price(1)->build();
        $this->userId = 'user-id';
        $this->productOption1 = new ProductOption('option-1', $this->product1->categoryId(), 'Option 1', 0.5);
        $this->productOption2 = new ProductOption('option-2', $this->product2->categoryId(), 'Option 2', 1.5);
        $this->productSupplement1a = new ProductSupplement('sup-1a', $this->product1->categoryId(), 'Supp 1a', 0.26);
        $this->productSupplement2a = new ProductSupplement('sup-2a', $this->product2->categoryId(), 'Supp 2a', 0.52);

        $this->productRepository = new InMemoryProductRepository();
        $this->basketRepository = new InMemoryBasketRepository();
        $this->productOptionRepository = new InMemoryProductOptionRepository();
        $this->productSupplementRepository = new InMemoryProductSupplementRepository();

        $this->productRepository->add($this->product1);
        $this->productRepository->add($this->product2);
        $this->productOptionRepository->add($this->productOption1);
        $this->productOptionRepository->add($this->productOption2);
        $this->productSupplementRepository->add($this->productSupplement1a);
        $this->productSupplementRepository->add($this->productSupplement2a);

        $this->addToBasket = new AddProductToBasket(
            $this->basketRepository,
            $this->productRepository,
            $this->productOptionRepository,
            $this->productSupplementRepository
        );
    }

    public function present(AddProductToBasketResponse $response): void
    {
        $this->response = $response;
    }

    public function test_it_adds_a_product_in_the_basket()
    {
        $this->addToBasket->execute(AddProductToBasketRequest::fromAll(1, $this->userId, $this->product1->id()), $this);

        $basket = $this->basketRepository->getUserBasket($this->userId);

        $this->assertCount(1, $basket);
        $this->assertSame($this->product1->name(), $basket[0]->name());
        $this->assertSame($this->product1->price(), $basket[0]->price());
    }

    public function test_it_calculate_the_total_price()
    {
        $firstBasketPrice = 1.2;
        $this->basketRepository->addToBasket($this->userId, new BasketProduct('basket-1', 1, 'first product', $firstBasketPrice, null, [], ''));

        $this->addToBasket->execute(AddProductToBasketRequest::fromAll(1, $this->userId, $this->product1->id()), $this);

        $basket = $this->basketRepository->getUserBasket($this->userId);

        $this->assertCount(2, $basket);
        $this->assertSame($firstBasketPrice + $this->product1->price(), $basket->totalPrice());
    }

    public function test_impossible_to_add_a_non_existing_product()
    {
        $this->addToBasket->execute(AddProductToBasketRequest::fromAll(1, $this->userId, 'unknown product id'), $this);

        $this->assertEquals(
            (new Notification())->addError('productId', 'unknown-product'),
            $this->response->notification()
        );
        $this->assertNull($this->response->basketProduct());
    }

    public function test_can_add_a_product_with_an_option_to_the_basket()
    {
        $this->addToBasket->execute(AddProductToBasketRequest::fromAll(1, $this->userId, $this->product1->id(), $this->productOption1->id()), $this);

        $basket = $this->basketRepository->getUserBasket($this->userId);

        $this->assertCount(1, $basket);
        $this->assertSame($this->product1->price() + $this->productOption1->price(), $basket->totalPrice());
    }

    public function test_throws_a_error_when_the_option_does_not_exist()
    {
        $this->addToBasket->execute(AddProductToBasketRequest::fromAll(1, $this->userId, $this->product1->id(), 'unknown option id'), $this);

        $this->assertEquals(
            (new Notification())->addError('optionId', 'unknown-option'),
            $this->response->notification()
        );
        $this->assertNull($this->response->basketProduct());
    }

    public function test_throws_a_error_when_the_option_is_not_linked_to_the_category_of_the_product()
    {
        $this->addToBasket->execute(AddProductToBasketRequest::fromAll(1, $this->userId, $this->product1->id(), $this->productOption2->id()), $this);

        $this->assertEquals(
            (new Notification())->addError('optionId', 'unknown-option'),
            $this->response->notification()
        );
    }

    public function test_can_add_supplements_on_product()
    {
        $this->addToBasket->execute(
            AddProductToBasketRequest::fromAll(1, $this->userId, $this->product1->id(), null, [$this->productSupplement1a->id()]),
            $this
        );

        $basket = $this->basketRepository->getUserBasket($this->userId);
        $this->assertCount(1, $basket);
        $this->assertSame($this->product1->price() + $this->productSupplement1a->price(), $basket->totalPrice());
    }

    public function test_throws_an_error_when_a_supplement_is_unknown()
    {
        $this->addToBasket->execute(AddProductToBasketRequest::fromAll(1, $this->userId, $this->product1->id(), null, ['unknown supplement']), $this);

        $this->assertEquals(
            (new Notification())->addError('supplementIds', 'unknown-supplement'),
            $this->response->notification()
        );
        $this->assertNull($this->response->basketProduct());
    }

    public function test_throws_an_error_when_a_supplement_is_not_linked_to_the_category_of_the_product()
    {
        $this->addToBasket->execute(
            AddProductToBasketRequest::fromAll(1, $this->userId, $this->product1->id(), null, [$this->productSupplement2a->id()]),
            $this
        );

        $this->assertEquals(
            (new Notification())->addError('supplementIds', 'unknown-supplement'),
            $this->response->notification()
        );

        $this->assertNull($this->response->basketProduct());
    }

    public function test_it_is_possible_to_add_a_comment()
    {
        $this->addToBasket->execute(
            AddProductToBasketRequest::fromAll(1, $this->userId, $this->product1->id(), null, [], 'This is a comment'),
            $this
        );

        $basket = $this->basketRepository->getUserBasket($this->userId);
        $this->assertCount(1, $basket);
        $this->assertSame('This is a comment', $basket[0]->comment());
    }

    public function test_it_is_possible_to_add_a_quantity()
    {
        $quantity = 3;
        $this->addToBasket->execute(
            AddProductToBasketRequest::fromAll(
                $quantity,
                $this->userId,
                $this->product1->id(),
                $this->productOption1->id(),
                [$this->productSupplement1a->id()],
                'No comment'
            ),
            $this
        );

        $basket = $this->basketRepository->getUserBasket($this->userId);
        $this->assertCount(1, $basket);
        $this->assertSame(11.88, $basket->totalPrice());
    }
}
