<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Context;

final class CachedEmailContext implements EmailContextInterface
{
    private EmailContextInterface $decorated;

    private ?string $email;

    public function __construct(EmailContextInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function getEmail(): ?string
    {
        if (!isset($this->email)) {
            $this->email = $this->decorated->getEmail();
        }

        return $this->email;
    }
}
