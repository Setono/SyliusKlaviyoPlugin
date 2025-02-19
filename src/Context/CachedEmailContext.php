<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Context;

final class CachedEmailContext implements EmailContextInterface
{
    private ?string $email = '';

    public function __construct(private readonly EmailContextInterface $decorated)
    {
    }

    public function getEmail(): ?string
    {
        if ('' === $this->email) {
            $this->email = $this->decorated->getEmail();
        }

        return $this->email;
    }
}
