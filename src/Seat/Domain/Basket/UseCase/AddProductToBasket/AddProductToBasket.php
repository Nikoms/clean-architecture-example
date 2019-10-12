<?php declare(strict_types=1);

namespace Seat\Domain\Basket\UseCase\AddProductToBasket;

use Assert\Assert;
use Assert\LazyAssertionException;
use Ramsey\Uuid\Uuid;
use Seat\Domain\Basket\Entity\BasketRepository;
use Seat\Domain\Menu\Entity\Product;
use Seat\Domain\Menu\Entity\ProductOptionRepository;
use Seat\Domain\Menu\Entity\ProductRepository;
use Seat\Domain\Menu\Entity\ProductSupplementRepository;
use Seat\Domain\Basket\Model\BasketProduct;
use Seat\Domain\Basket\Model\BasketProductOption;
use Seat\Domain\Basket\Model\BasketProductSupplement;

class AddProductToBasket
{
    private $basketRepository;
    private $productRepository;
    private $productOptionRepository;
    private $productSupplementRepository;

    public function __construct(
        BasketRepository $basketRepository,
        ProductRepository $productRepository,
        ProductOptionRepository $productOptionRepository,
        ProductSupplementRepository $productSupplementRepository
    ) {
        $this->basketRepository = $basketRepository;
        $this->productRepository = $productRepository;
        $this->productOptionRepository = $productOptionRepository;
        $this->productSupplementRepository = $productSupplementRepository;
    }

    /** @throws */
    public function execute(AddProductToBasketRequest $request, AddProductToBasketPresenter $presenter)
    {
        $response = new AddProductToBasketResponse();
        $isValid = $this->checkRequest($request, $response);

        if ($isValid) {
            $product = $this->getProduct($request, $response);
            if ($product) {
                $option = $this->getOption($request, $response, $product);
                $supplements = $this->getSupplements($request, $response, $product);

                if (!$response->notification()->hasError()) {
                    $basketProduct = new BasketProduct(
                        Uuid::uuid4()->toString(),
                        $request->quantity,
                        $product->name(),
                        $product->price(),
                        $option,
                        $supplements,
                        (string)$request->comment
                    );

                    $this->basketRepository->addToBasket($request->userId, $basketProduct);

                    $response->setBasketProduct($basketProduct);
                }

            }

        }

        $presenter->present($response);

    }

    private function getProduct(AddProductToBasketRequest $basketData, AddProductToBasketResponse $response)
    {
        $product = $this->productRepository->get($basketData->productId);

        if ($product === null) {
            $response->addError('productId', 'unknown-product');
        }

        return $product;
    }

    private function getSupplements(AddProductToBasketRequest $basketData, AddProductToBasketResponse $response, Product $product): array
    {
        $basketProductSupplements = [];
        foreach ($basketData->supplementIds as $supplementId) {
            $supplement = $this->productSupplementRepository->get($supplementId);
            if ($supplement === null) {
                $response->addError('supplementIds', 'unknown-supplement');

                return [];
            }
            if ($supplement->categoryId() !== $product->categoryId()) {
                $response->addError('supplementIds', 'unknown-supplement');

                return [];
            }
            $basketProductSupplements[] = new BasketProductSupplement($supplement->name(), $supplement->price());
        }

        return $basketProductSupplements;
    }

    private function getOption(AddProductToBasketRequest $basketData, AddProductToBasketResponse $response, Product $product)
    {
        if ($basketData->optionId === null) {
            return null;
        }

        $productOption = $this->productOptionRepository->get($basketData->optionId);
        if ($productOption === null || $productOption->categoryId() !== $product->categoryId()) {
            $response->addError('optionId', 'unknown-option');

            return null;
        }

        return new BasketProductOption($productOption->name(), $productOption->price());

    }

    private function checkRequest(AddProductToBasketRequest $request, AddProductToBasketResponse $response): bool
    {
        try {
            Assert::lazy()
                ->that($request->quantity, 'quantity')->notEmpty('error-notEmpty')->integer('error-integer')
                ->that($request->userId, 'userId')->notEmpty('error-notEmpty')->string('error-string')
                ->that($request->productId, 'productId')->notEmpty('error-notEmpty')->string('error-string')
                ->that($request->optionId, 'optionId')->nullOr()->string('error-string')
                ->that($request->supplementIds, 'supplementIds')->isArray()
                ->that($request->comment, 'comment')->nullOr()->string('error-string')
                ->verifyNow();

            return true;
        } catch (LazyAssertionException $e) {
            foreach ($e->getErrorExceptions() as $error) {
                $response->addError($error->getPropertyPath(), $error->getMessage());
            }

            return false;
        }
    }
}
