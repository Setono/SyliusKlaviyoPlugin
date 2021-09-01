<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO;

abstract class Event
{
    public string $token;

    public string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }
}
