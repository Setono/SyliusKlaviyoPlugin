<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class StoreExchangeSubscriber implements EventSubscriberInterface
{
    private const EXCHANGE_QUERY_PARAMETER = '_kx';

    private string $cookieName;

    public function __construct(string $cookieName)
    {
        $this->cookieName = $cookieName;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => 'store',
        ];
    }

    public function store(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();

        if (!$request->query->has(self::EXCHANGE_QUERY_PARAMETER)) {
            return;
        }

        $exchange = $request->query->get(self::EXCHANGE_QUERY_PARAMETER);
        if (!is_string($exchange)) {
            return;
        }

        $event->getResponse()->headers->setCookie(
            Cookie::create($this->cookieName, $exchange, new \DateTimeImmutable('+1 year'))
        );
    }
}
