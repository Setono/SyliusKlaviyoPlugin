<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Message\Handler;

use Setono\SyliusKlaviyoPlugin\Client\RestClientInterface;
use Setono\SyliusKlaviyoPlugin\Message\Command\SubscribeCustomer;
use Sylius\Component\Core\Repository\CustomerRepositoryInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class SubscribeCustomerHandler
{
    private RestClientInterface $client;

    private CustomerRepositoryInterface $customerRepository;

    public function __construct(RestClientInterface $client, CustomerRepositoryInterface $customerRepository)
    {
        $this->client = $client;
        $this->customerRepository = $customerRepository;
    }

    public function __invoke(SubscribeCustomer $message): void
    {
        /** @var CustomerInterface|null $customer */
        $customer = $this->customerRepository->find($message->getCustomerId());
        if (null === $customer) {
            return;
        }

        $this->client->post(sprintf('list/%s/subscribe', $message->getListId()), [
            'profiles' => [
                'email' => $customer->getEmailCanonical(),
            ],
        ]);
    }
}
