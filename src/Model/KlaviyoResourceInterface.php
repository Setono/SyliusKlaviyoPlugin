<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Model;

interface KlaviyoResourceInterface
{
    /**
     * The id in Klaviyo
     */
    public function getKlaviyoId(): ?string;

    public function setKlaviyoId(?string $klaviyoId): void;
}
