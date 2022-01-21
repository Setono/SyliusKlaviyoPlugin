<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO\Properties;

interface BrandAwareInterface
{
    public function getBrandName(string $locale = null): string;
}
