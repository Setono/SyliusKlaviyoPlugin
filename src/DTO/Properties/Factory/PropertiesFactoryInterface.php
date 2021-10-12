<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO\Properties\Factory;

use Setono\SyliusKlaviyoPlugin\DTO\Properties\Base;

interface PropertiesFactoryInterface
{
    /**
     * @template T of Base
     *
     * @param class-string<T> $class
     * @param object|array<array-key, object> $hydratableObjects
     *
     * @return T
     */
    public function create(string $class, $hydratableObjects = []): Base;
}
