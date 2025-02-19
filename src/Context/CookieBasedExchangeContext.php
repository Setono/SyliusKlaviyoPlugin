<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Context;

use Symfony\Component\HttpFoundation\RequestStack;

final class CookieBasedExchangeContext implements ExchangeContextInterface
{
    public function __construct(private readonly ExchangeContextInterface $decorated, private readonly RequestStack $requestStack, private readonly string $cookieName)
    {
    }

    public function getExchange(): ?string
    {
        $request = $this->requestStack->getMainRequest();
        if (null === $request) {
            return $this->decorated->getExchange();
        }

        $cookieValue = $request->cookies->get($this->cookieName);

        return is_string($cookieValue) && '' !== $cookieValue ? $cookieValue : $this->decorated->getExchange();
    }
}
