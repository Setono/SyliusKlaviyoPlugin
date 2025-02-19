<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Context;

use Symfony\Component\HttpFoundation\RequestStack;

final class CookieBasedEmailContext implements EmailContextInterface
{
    public function __construct(private readonly EmailContextInterface $decorated, private readonly RequestStack $requestStack, private readonly string $cookieName)
    {
    }

    public function getEmail(): ?string
    {
        $request = $this->requestStack->getMainRequest();
        if (null === $request) {
            return $this->decorated->getEmail();
        }

        if (!$request->cookies->has($this->cookieName)) {
            return $this->decorated->getEmail();
        }

        $cookieValue = $request->cookies->get($this->cookieName);
        if (!is_string($cookieValue) || '' === $cookieValue) {
            return $this->decorated->getEmail();
        }

        $email = base64_decode($cookieValue);
        if ('' === $email) {
            return $this->decorated->getEmail();
        }

        return $email;
    }
}
