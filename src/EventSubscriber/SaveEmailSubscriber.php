<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\EventSubscriber;

use Setono\SyliusKlaviyoPlugin\Context\EmailContextInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Webmozart\Assert\Assert;

final class SaveEmailSubscriber implements EventSubscriberInterface
{
    private ?string $email = null;

    private EmailContextInterface $emailContext;

    private string $cookieName;

    public function __construct(EmailContextInterface $emailContext, string $cookieName)
    {
        $this->emailContext = $emailContext;
        $this->cookieName = $cookieName;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'sylius.order.post_address' => 'retrieveEmail',
            'sylius.order.post_select_shipping' => 'retrieveEmail',
            'sylius.order.post_payment' => 'retrieveEmail',
            'sylius.order.post_complete' => 'retrieveEmail',
            KernelEvents::RESPONSE => 'saveEmail',
        ];
    }

    public function retrieveEmail(GenericEvent $event): void
    {
        /** @var OrderInterface|mixed $order */
        $order = $event->getSubject();
        Assert::isInstanceOf($order, OrderInterface::class);

        $customer = $order->getCustomer();
        if (null === $customer) {
            return;
        }

        if (!$customer->isSubscribedToNewsletter()) {
            return;
        }

        $this->email = $customer->getEmailCanonical();
    }

    public function saveEmail(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        // this ensures that we update the cookie on each request
        $email = $this->email ?? $this->emailContext->getEmail();
        if (null === $email) {
            return;
        }

        $response = $event->getResponse();
        $response->headers->setCookie(
            Cookie::create($this->cookieName, base64_encode($email), new \DateTimeImmutable('+360 days'))
        );
    }
}
