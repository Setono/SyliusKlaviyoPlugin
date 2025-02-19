<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Synchronizer;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Setono\SyliusKlaviyoPlugin\Client\RestClientInterface;
use Setono\SyliusKlaviyoPlugin\DTO\Response\ListData;
use Setono\SyliusKlaviyoPlugin\Model\MemberListInterface;
use Setono\SyliusKlaviyoPlugin\Repository\MemberListRepositoryInterface;
use Sylius\Resource\Factory\FactoryInterface;
use Webmozart\Assert\Assert;

final class ListSynchronizer implements ListSynchronizerInterface
{
    private LoggerInterface $logger;

    public function __construct(
        private readonly RestClientInterface $restClient,
        private readonly MemberListRepositoryInterface $listRepository,
        private readonly FactoryInterface $listFactory,
    ) {
        $this->logger = new NullLogger();
    }

    public function synchronize(): void
    {
        $this->logger->debug('Synchronizing lists from Klaviyo');

        $ids = [];
        $klaviyoLists = $this->restClient->get('lists')->toArray();

        foreach ($klaviyoLists['data'] as $klaviyoList) {
            Assert::isArray($klaviyoList);
            $dto = new ListData();
            $dto->list_id = $klaviyoList['id'];
            $dto->list_name = $klaviyoList['attributes']['name'];

            $entity = $this->listRepository->findOneByKlaviyoId($dto->list_id);

            if (null === $entity) {
                /** @var MemberListInterface $entity */
                $entity = $this->listFactory->createNew();
                $entity->setKlaviyoId($dto->list_id);
            }

            $entity->setName($dto->list_name);

            $this->listRepository->add($entity);

            $this->logger->debug(sprintf('Synchronized %s (id: %s)', $dto->list_name, $dto->list_id));

            $ids[] = (int) $entity->getId();
        }

        $this->listRepository->deleteAllBut($ids);
    }

    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }
}
