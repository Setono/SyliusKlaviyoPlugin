<?php
declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\EventSubscriber;

use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Core\Model\ProductInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Webmozart\Assert\Assert;

final class ViewedProductSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.product.show' => 'track'
        ];
    }

    public function track(ResourceControllerEvent $event): void
    {
        /** @var ProductInterface|mixed $product */
        $product = $event->getSubject();
        Assert::isInstanceOf($product, ProductInterface::class);

        dd($product);
    }
}
