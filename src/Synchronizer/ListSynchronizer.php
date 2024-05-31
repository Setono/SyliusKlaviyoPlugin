<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Synchronizer;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Setono\SyliusKlaviyoPlugin\Client\RestClientInterface;
use Setono\SyliusKlaviyoPlugin\DTO\Response\ListData;
use Setono\SyliusKlaviyoPlugin\Model\MemberListInterface;
use Setono\SyliusKlaviyoPlugin\Repository\MemberListRepositoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Webmozart\Assert\Assert;

final class ListSynchronizer implements ListSynchronizerInterface
{
    private LoggerInterface $logger;

    private RestClientInterface $restClient;

    private MemberListRepositoryInterface $listRepository;

    private FactoryInterface $listFactory;

    public function __construct(
        RestClientInterface $restClient,
        MemberListRepositoryInterface $listRepository,
        FactoryInterface $listFactory
    ) {
        $this->logger = new NullLogger();
        $this->restClient = $restClient;
        $this->listRepository = $listRepository;
        $this->listFactory = $listFactory;
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
