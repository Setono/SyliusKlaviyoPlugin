<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Message\Command;

use Sylius\Component\Customer\Model\CustomerInterface;
use Webmozart\Assert\Assert;

final class SubscribeCustomer implements CommandInterface
{
    private readonly int $customerId;

    /**
     * @param int|CustomerInterface|mixed $customer
     */
    public function __construct($customer, private readonly string $listId)
    {
        if ($customer instanceof CustomerInterface) {
            $customer = (int) $customer->getId();
        }
        Assert::integer($customer);

        $this->customerId = $customer;
    }

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function getListId(): string
    {
        return $this->listId;
    }
}
