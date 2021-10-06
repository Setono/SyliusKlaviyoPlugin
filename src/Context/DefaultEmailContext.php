<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Context;

final class DefaultEmailContext implements EmailContextInterface
{
    public function getEmail(): ?string
    {
        return null;
    }
}
