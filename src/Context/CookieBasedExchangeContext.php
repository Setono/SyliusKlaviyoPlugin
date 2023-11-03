<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Context;

use Symfony\Component\HttpFoundation\RequestStack;

final class CookieBasedExchangeContext implements ExchangeContextInterface
{
    private ExchangeContextInterface $decorated;

    private RequestStack $requestStack;

    private string $cookieName;

    public function __construct(ExchangeContextInterface $decorated, RequestStack $requestStack, string $cookieName)
    {
        $this->decorated = $decorated;
        $this->requestStack = $requestStack;
        $this->cookieName = $cookieName;
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
