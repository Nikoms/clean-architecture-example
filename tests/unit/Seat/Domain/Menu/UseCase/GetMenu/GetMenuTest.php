<?php declare(strict_types=1);

namespace SeatTest\Domain\Menu\UseCase\GetMenu;

use PHPUnit\Framework\TestCase;
use Seat\Domain\Menu\UseCase\GetMenu\GetMenu;
use Seat\Domain\Menu\UseCase\GetMenu\GetMenuPresenter;
use Seat\Domain\Menu\UseCase\GetMenu\GetMenuResponse;
use Seat\Domain\Menu\Entity\Category;
use Seat\Domain\Menu\Entity\Product;
use Seat\Domain\Menu\Entity\ProductOption;
use Seat\Domain\Menu\Entity\ProductSupplement;
use SeatTest\_Mock\Domain\Menu\Entity\InMemoryCategoryRepository;
use SeatTest\_Mock\Domain\Menu\Entity\InMemoryProductOptionRepository;
use SeatTest\_Mock\Domain\Menu\Entity\InMemoryProductRepository;
use SeatTest\_Mock\Domain\Menu\Entity\InMemoryProductSupplementRepository;

class GetMenuTest extends TestCase implements GetMenuPresenter
{
    private $categoryRepository;
    private $optionRepository;
    private $getMenu;

    private $category1;
    private $category2;
    private $supplementRepository;
    private $productRepository;
    /** @var GetMenuResponse */
    private $response;

    protected function setUp()
    {
        $this->categoryRepository = new InMemoryCategoryRepository();
        $this->optionRepository = new InMemoryProductOptionRepository();
        $this->supplementRepository = new InMemoryProductSupplementRepository();
        $this->productRepository = new InMemoryProductRepository();
        $this->category1 = new Category('category-1', 'Cat 1', 'My first category');
        $this->category2 = new Category('category-2', 'Cat 2', 'My second category');

        $this->categoryRepository->addCategory($this->category1);
        $this->categoryRepository->addCategory($this->category2);

        $this->getMenu = new GetMenu(
            $this->categoryRepository,
            $this->optionRepository,
            $this->supplementRepository,
            $this->productRepository
        );
    }

    public function present(GetMenuResponse $response): void
    {
        $this->response = $response;
    }

    public function test_it_returns_a_list_of_category()
    {
        $this->getMenu->execute($this);

        $this->assertCount(2, $this->response->menuLines);
        $this->assertSame($this->category1->name(), $this->response->menuLines[0]->name());
        $this->assertSame($this->category1->description(), $this->response->menuLines[0]->description());

        $this->assertSame($this->category2->name(), $this->response->menuLines[1]->name());
        $this->assertSame($this->category2->description(), $this->response->menuLines[1]->description());
    }

    public function test_the_list_may_contains_options()
    {
        $option1a = new ProductOption('option-1a', $this->category1->id(), 'Option 1a', 1);
        $option1b = new ProductOption('option-1b', $this->category1->id(), 'Option 1b', 1);
        $this->optionRepository->add($option1a);
        $this->optionRepository->add($option1b);

        $this->getMenu->execute($this);

        $this->assertCount(2, $this->response->menuLines[0]->options());

        $this->assertSame($option1a->id(), $this->response->menuLines[0]->options()[0]->id());
        $this->assertSame($option1a->name(), $this->response->menuLines[0]->options()[0]->name());
        $this->assertSame($option1a->price(), $this->response->menuLines[0]->options()[0]->price());

        $this->assertSame($option1b->id(), $this->response->menuLines[0]->options()[1]->id());
        $this->assertSame($option1b->name(), $this->response->menuLines[0]->options()[1]->name());
        $this->assertSame($option1b->price(), $this->response->menuLines[0]->options()[1]->price());
    }

    public function test_the_list_may_contains_supplements()
    {
        $supplement1a = new ProductSupplement('supplement-1a', $this->category1->id(), 'Supplement 1a', 1);
        $supplement1b = new ProductSupplement('supplement-1b', $this->category1->id(), 'Supplement 1b', 1);
        $this->supplementRepository->add($supplement1a);
        $this->supplementRepository->add($supplement1b);

        $this->getMenu->execute($this);

        $this->assertCount(2, $this->response->menuLines[0]->supplements());

        $this->assertSame($supplement1a->id(), $this->response->menuLines[0]->supplements()[0]->id());
        $this->assertSame($supplement1a->name(), $this->response->menuLines[0]->supplements()[0]->name());
        $this->assertSame($supplement1a->price(), $this->response->menuLines[0]->supplements()[0]->price());

        $this->assertSame($supplement1b->id(), $this->response->menuLines[0]->supplements()[1]->id());
        $this->assertSame($supplement1b->name(), $this->response->menuLines[0]->supplements()[1]->name());
        $this->assertSame($supplement1b->price(), $this->response->menuLines[0]->supplements()[1]->price());
    }

    public function test_the_list_may_contains_products()
    {
        $product1a = new Product('product-1a', $this->category1->id(), 'Product 1a', 'Product 1a desc', 1);
        $product1b = new Product('product-1b', $this->category1->id(), 'Product 1b', 'Product 1b desc', 1);
        $this->productRepository->add($product1a);
        $this->productRepository->add($product1b);

        $this->getMenu->execute($this);

        $this->assertCount(2, $this->response->menuLines[0]->products());

        $this->assertSame($product1a->id(), $this->response->menuLines[0]->products()[0]->id());
        $this->assertSame($product1a->name(), $this->response->menuLines[0]->products()[0]->name());
        $this->assertSame($product1a->price(), $this->response->menuLines[0]->products()[0]->price());
        $this->assertSame($product1a->description(), $this->response->menuLines[0]->products()[0]->description());

        $this->assertSame($product1b->id(), $this->response->menuLines[0]->products()[1]->id());
        $this->assertSame($product1b->name(), $this->response->menuLines[0]->products()[1]->name());
        $this->assertSame($product1b->price(), $this->response->menuLines[0]->products()[1]->price());
        $this->assertSame($product1b->description(), $this->response->menuLines[0]->products()[1]->description());
    }
}
