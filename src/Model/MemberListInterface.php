<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Model;

use Sylius\Component\Resource\Model\ResourceInterface;

interface MemberListInterface extends ResourceInterface, KlaviyoResourceInterface
{
    public function getId(): ?int;

    public function getName(): ?string;

    public function setName(string $name): void;
}
