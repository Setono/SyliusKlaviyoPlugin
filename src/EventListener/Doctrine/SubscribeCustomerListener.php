<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\EventListener\Doctrine;

use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Setono\SyliusKlaviyoPlugin\Message\Command\SubscribeCustomer;
use Setono\SyliusKlaviyoPlugin\Model\MemberListInterface;
use Setono\SyliusKlaviyoPlugin\Repository\MemberListRepositoryInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Customer\Model\CustomerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class SubscribeCustomerListener
{
    public function __construct(private readonly MessageBusInterface $commandBus, private readonly ChannelContextInterface $channelContext, private readonly MemberListRepositoryInterface $memberListRepository)
    {
    }

    public function postPersist(PostPersistEventArgs $eventArgs): void
    {
        $customer = $eventArgs->getObject();
        if (!$customer instanceof CustomerInterface) {
            return;
        }

        if (!$customer->isSubscribedToNewsletter()) {
            return;
        }

        $this->subscribe($customer);
    }

    public function preUpdate(PreUpdateEventArgs $eventArgs): void
    {
        $customer = $eventArgs->getObject();
        if (!$customer instanceof CustomerInterface) {
            return;
        }

        if (!$eventArgs->hasChangedField('subscribedToNewsletter') || $eventArgs->getNewValue('subscribedToNewsletter') === false) {
            return;
        }

        $this->subscribe($customer);
    }

    private function subscribe(CustomerInterface $customer): void
    {
        foreach ($this->getListIds() as $listId) {
            $this->commandBus->dispatch(new SubscribeCustomer($customer, $listId));
        }
    }

    /**
     * @return array<array-key, string>
     */
    private function getListIds(): array
    {
        return array_map(
            static fn (MemberListInterface $list): string => (string) $list->getKlaviyoId(),
            $this->memberListRepository->findByChannel($this->channelContext->getChannel()),
        );
    }
}
