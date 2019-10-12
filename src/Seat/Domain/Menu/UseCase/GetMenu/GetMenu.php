<?php declare(strict_types=1);

namespace Seat\Domain\Menu\UseCase\GetMenu;

use Seat\Domain\Menu\Entity\Category;
use Seat\Domain\Menu\Entity\CategoryRepository;
use Seat\Domain\Menu\Entity\Product;
use Seat\Domain\Menu\Entity\ProductOption;
use Seat\Domain\Menu\Entity\ProductOptionRepository;
use Seat\Domain\Menu\Entity\ProductRepository;
use Seat\Domain\Menu\Entity\ProductSupplement;
use Seat\Domain\Menu\Entity\ProductSupplementRepository;
use Seat\Domain\Menu\Model\MenuLine;
use Seat\Domain\Menu\Model\MenuOption;
use Seat\Domain\Menu\Model\MenuProduct;
use Seat\Domain\Menu\Model\MenuSupplement;

class GetMenu
{
    private $categoryRepository;
    private $optionRepository;
    private $supplementRepository;
    private $productRepository;

    public function __construct(
        CategoryRepository $categoryRepository,
        ProductOptionRepository $optionRepository,
        ProductSupplementRepository $supplementRepository,
        ProductRepository $productRepository
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->optionRepository = $optionRepository;
        $this->supplementRepository = $supplementRepository;
        $this->productRepository = $productRepository;
    }

    public function execute(GetMenuPresenter $presenter)
    {
        $menu = [];
        foreach ($this->categoryRepository->getCategories() as $category) {
            $menu[] = new MenuLine(
                $category->name(),
                $category->description(),
                $this->getProductsOf($category),
                $this->getOptionsOf($category),
                $this->getSupplementsOf($category)
            );
        }

        $presenter->present(new GetMenuResponse($menu));
    }

    private function getOptionsOf(Category $category): array
    {
        $options = $this->optionRepository->getByCategoryId($category->id());
        $transformToMenuOption = function (ProductOption $option) {
            return new MenuOption($option->id(), $option->name(), $option->price());
        };

        return array_map($transformToMenuOption, $options);
    }

    private function getProductsOf(Category $category): array
    {
        $products = $this->productRepository->getByCategoryId($category->id());
        $transformToMenuProduct = function (Product $product) {
            return new MenuProduct($product->id(), $product->name(), $product->description(), $product->price());
        };

        return array_map($transformToMenuProduct, $products);
    }

    private function getSupplementsOf(Category $category): array
    {
        $supplements = $this->supplementRepository->getByCategoryId($category->id());
        $transformToMenuSupplement = function (ProductSupplement $supplement) {
            return new MenuSupplement($supplement->id(), $supplement->name(), $supplement->price());
        };

        return array_map($transformToMenuSupplement, $supplements);
    }
}
