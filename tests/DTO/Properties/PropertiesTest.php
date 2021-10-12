<?php

declare(strict_types=1);

namespace Tests\Setono\SyliusKlaviyoPlugin\DTO\Properties;

use PHPUnit\Framework\TestCase;
use Setono\SyliusKlaviyoPlugin\DTO\Properties\Properties;
use Sylius\Component\Core\Model\Order;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Core\Model\ProductInterface;
use Symfony\Component\DependencyInjection\Container;

/**
 * @covers \Setono\SyliusKlaviyoPlugin\DTO\Properties\Properties
 */
final class PropertiesTest extends TestCase
{
    /**
     * @test
     */
    public function it_populates_on_creation(): void
    {
        $order = new Order();
        $product = new Product();

        $obj = new class(new Container(), [$order, $product]) extends Properties {
            public bool $populatedOrder = false;

            public bool $populatedProduct = false;

            protected function populateFromOrder(OrderInterface $order): void
            {
                $this->populatedOrder = true;
            }

            protected function populateFromProduct(ProductInterface $product): void
            {
                $this->populatedProduct = true;
            }
        };

        self::assertTrue($obj->populatedOrder);
        self::assertTrue($obj->populatedProduct);
    }
}
