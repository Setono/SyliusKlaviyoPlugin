<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\DTO\Properties;

use Sylius\Component\Core\Model\ProductInterface;

trait TaxonTrait
{
    /**
     * @return list<string>
     */
    protected static function getTaxonsFromProduct(ProductInterface $product): array
    {
        $taxons = [];

        $mainTaxon = $product->getMainTaxon();
        if (null !== $mainTaxon) {
            $taxons[(string) $mainTaxon->getCode()] = (string) $mainTaxon->getName();
        }

        foreach ($product->getTaxons() as $taxon) {
            $taxons[(string) $taxon->getCode()] = (string) $taxon->getName();
        }

        return array_values($taxons);
    }

    /**
     * @param array<array-key, Item> $items
     *
     * @return list<string>
     */
    protected static function getTaxonsFromItems(array $items): array
    {
        $taxons = [];

        foreach ($items as $item) {
            foreach ($item->categories as $category) {
                $taxons[$category] = $category;
            }
        }

        return array_values($taxons);
    }
}
