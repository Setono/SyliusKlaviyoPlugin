<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO\Properties;

use Sylius\Component\Core\Model\OrderInterface;

/**
 * Docs: https://help.klaviyo.com/hc/en-us/articles/115005082927#started-checkout5
 */
class StartedCheckoutProperties extends Properties
{
    use MoneyFormatterTrait;

    use TaxonTrait;

    /** @psalm-readonly */
    public ?string $event = 'Started Checkout';

    /** @var array<array-key, string> */
    public array $itemNames = [];

    public ?string $checkoutUrl = null;

    /** @var array<array-key, string> */
    public array $categories = [];

    /** @var array<array-key, Item> */
    public array $items = [];

    public function populateFromOrder(OrderInterface $order): void
    {
        $this->eventId = sprintf('%s_%s', (string) $order->getNumber(), (new \DateTimeImmutable())->format('U.u'));
        $this->checkoutUrl = $this->getUrlGenerator()->generate('sylius_shop_checkout_start');
        $this->value = self::formatAmount($order->getTotal());

        foreach ($order->getItems() as $item) {
            $this->itemNames[] = (string) $item->getProductName();
            $this->items[] = $this->getPropertiesFactory()->create(Item::class, $item);
        }

        $this->categories = self::getTaxonsFromItems($this->items);
    }
}
