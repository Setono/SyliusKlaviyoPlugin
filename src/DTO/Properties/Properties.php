<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO\Properties;

abstract class Properties
{
    /**
     * The name of the associated event, i.e. 'Viewed Product'
     */
    public ?string $event = null;

    public ?string $eventId = null;

    public ?float $value = null;
}
