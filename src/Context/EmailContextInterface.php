<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Context;

interface EmailContextInterface
{
    /**
     * Returns the email of the visitor if possible, else it returns null
     */
    public function getEmail(): ?string;
}
