<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Repository;

use Setono\SyliusKlaviyoPlugin\Model\MemberListInterface;
use Sylius\Component\Channel\Model\ChannelInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;

/**
 * @extends RepositoryInterface<MemberListInterface>
 */
interface MemberListRepositoryInterface extends RepositoryInterface
{
    public function findOneByKlaviyoId(string $klaviyoId): ?MemberListInterface;

    /**
     * @return array<array-key, MemberListInterface>
     */
    public function findByChannel(ChannelInterface $channel): array;

    /**
     * @param array<array-key, int> $ids
     */
    public function deleteAllBut(array $ids): void;
}
