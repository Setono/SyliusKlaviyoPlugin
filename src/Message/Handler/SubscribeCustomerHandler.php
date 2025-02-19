<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Message\Handler;

use Setono\SyliusKlaviyoPlugin\Client\RestClientInterface;
use Setono\SyliusKlaviyoPlugin\DTO\Properties\CustomerProperties;
use Setono\SyliusKlaviyoPlugin\DTO\Properties\Factory\PropertiesFactoryInterface;
use Setono\SyliusKlaviyoPlugin\Message\Command\SubscribeCustomer;
use Sylius\Component\Core\Repository\CustomerRepositoryInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[AsMessageHandler]
final class SubscribeCustomerHandler
{
    public function __construct(private readonly RestClientInterface $client, private readonly CustomerRepositoryInterface $customerRepository, private readonly SerializerInterface $serializer, private readonly PropertiesFactoryInterface $propertiesFactory)
    {
    }

    public function __invoke(SubscribeCustomer $message): void
    {
        /** @var CustomerInterface|null $customer */
        $customer = $this->customerRepository->find($message->getCustomerId());

        if (null === $customer) {
            return;
        }

        $profile = $this->serializer->serialize(
            $this->propertiesFactory->create(CustomerProperties::class, $customer),
            'json',
            [
                'groups' => 'setono:sylius-klaviyo:subscription',
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
            ],
        );

        $this->client->post('profile-subscription-bulk-create-jobs', [
            'data' => [
                'type' => 'profile-subscription-bulk-create-job',
                'attributes' => [
                    'profiles' => [
                        'data' => [json_decode($profile, true)],
                    ],
                ],
                'relationships' => [
                    'list' => [
                        'data' => [
                            'type' => 'list',
                            'id' => $message->getListId(),
                        ],
                    ],
                ],
            ],
        ]);
    }
}
