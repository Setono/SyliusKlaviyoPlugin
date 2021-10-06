<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Context;

use Symfony\Component\HttpFoundation\RequestStack;

final class CookieBasedEmailContext implements EmailContextInterface
{
    private EmailContextInterface $decorated;

    private RequestStack $requestStack;

    private string $cookieName;

    public function __construct(EmailContextInterface $decorated, RequestStack $requestStack, string $cookieName)
    {
        $this->decorated = $decorated;
        $this->requestStack = $requestStack;
        $this->cookieName = $cookieName;
    }

    public function getEmail(): ?string
    {
        $request = $this->requestStack->getMasterRequest();
        if (null === $request) {
            return $this->decorated->getEmail();
        }

        if (!$request->cookies->has($this->cookieName)) {
            return $this->decorated->getEmail();
        }

        $cookieValue = $request->cookies->get($this->cookieName);
        if (!is_string($cookieValue)) {
            return $this->decorated->getEmail();
        }

        $email = base64_decode($cookieValue);
        if (false === $email) {
            return $this->decorated->getEmail();
        }

        return $email;
    }
}
