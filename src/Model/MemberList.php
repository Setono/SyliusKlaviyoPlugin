<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Model;

/**
 * This class corresponds to the 'List' entity in Klaviyo. The reason for the naming is simple: PHP doesn't allow classes to be named 'List' ;)
 */
class MemberList implements MemberListInterface
{
    protected ?int $id = null;

    protected ?string $klaviyoId = null;

    protected ?string $name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKlaviyoId(): ?string
    {
        return $this->klaviyoId;
    }

    public function setKlaviyoId(?string $klaviyoId): void
    {
        $this->klaviyoId = $klaviyoId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}
