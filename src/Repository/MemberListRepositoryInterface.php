<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Repository;

use Setono\SyliusKlaviyoPlugin\Model\MemberListInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface MemberListRepositoryInterface extends RepositoryInterface
{
    public function findOneByKlaviyoId(string $klaviyoId): ?MemberListInterface;

    /**
     * @param array<array-key, int> $ids
     */
    public function deleteAllBut(array $ids): void;
}
