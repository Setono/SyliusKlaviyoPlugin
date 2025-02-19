<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\EventSubscriber;

use Setono\SyliusKlaviyoPlugin\DTO\Properties\CustomerProperties;
use Setono\SyliusKlaviyoPlugin\DTO\Properties\Factory\PropertiesFactoryInterface;
use Setono\SyliusKlaviyoPlugin\DTO\Properties\Location;
use Setono\SyliusKlaviyoPlugin\Event\PropertiesArePopulatedEvent;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class UpdateCustomerPropertiesFromOrderSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly PropertiesFactoryInterface $propertiesFactory)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PropertiesArePopulatedEvent::class => 'update',
        ];
    }

    public function update(PropertiesArePopulatedEvent $event): void
    {
        if (!isset($event->context['order']) || !$event->context['order'] instanceof OrderInterface) {
            return;
        }

        $this->populate($event->context['order'], $event->event->customerProperties);
    }

    private function populate(OrderInterface $order, CustomerProperties $customerProperties): void
    {
        $address = $order->getBillingAddress();
        if (null === $address) {
            return;
        }

        $customerProperties->firstName = $address->getFirstName();
        $customerProperties->lastName = $address->getLastName();
        $customerProperties->phoneNumber = $address->getPhoneNumber();
        $customerProperties->location = $this->propertiesFactory->create(Location::class, $address);
    }
}
