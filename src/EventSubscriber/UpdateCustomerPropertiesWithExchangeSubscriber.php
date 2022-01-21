<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\EventSubscriber;

use Setono\SyliusKlaviyoPlugin\Event\PropertiesArePopulatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class UpdateCustomerPropertiesWithExchangeSubscriber implements EventSubscriberInterface
{
    private RequestStack $requestStack;

    private string $cookieName;

    public function __construct(RequestStack $requestStack, string $cookieName)
    {
        $this->requestStack = $requestStack;
        $this->cookieName = $cookieName;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PropertiesArePopulatedEvent::class => 'update',
        ];
    }

    public function update(PropertiesArePopulatedEvent $event): void
    {
        $request = $this->requestStack->getMasterRequest();
        if (null === $request) {
            return;
        }

        if (!$request->cookies->has($this->cookieName)) {
            return;
        }

        $value = $request->cookies->get($this->cookieName);
        if (!is_string($value)) {
            return;
        }

        $event->event->customerProperties->exchangeId = $value;
    }
}
