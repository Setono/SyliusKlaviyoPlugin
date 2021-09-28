<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Doctrine\ORM;

use Setono\SyliusKlaviyoPlugin\Model\MemberListInterface;
use Setono\SyliusKlaviyoPlugin\Repository\MemberListRepositoryInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Webmozart\Assert\Assert;

class MemberListRepository extends EntityRepository implements MemberListRepositoryInterface
{
    public function findOneByKlaviyoId(string $klaviyoId): ?MemberListInterface
    {
        $obj = $this->createQueryBuilder('o')
            ->andWhere('o.klaviyoId = :klaviyoId')
            ->setParameter('klaviyoId', $klaviyoId)
            ->getQuery()
            ->getOneOrNullResult()
        ;

        Assert::nullOrIsInstanceOf($obj, MemberListInterface::class);

        return $obj;
    }

    public function deleteAllBut(array $ids): void
    {
        $this->createQueryBuilder('o')
            ->delete()
            ->andWhere('o.id NOT IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->execute()
        ;
    }
}
