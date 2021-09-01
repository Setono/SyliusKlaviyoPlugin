<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Model;

use Sylius\Component\Resource\Model\ResourceInterface;

interface MemberListInterface extends ResourceInterface
{
    public function getId(): ?int;

    /**
     * This is the list id in Klaviyo
     */
    public function getKlaviyoId(): ?string;

    public function setKlaviyoId(string $klaviyoId): void;

    public function getName(): ?string;

    public function setName(string $name): void;
}
