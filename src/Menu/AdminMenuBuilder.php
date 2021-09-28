<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuBuilder
{
    public function addSection(MenuBuilderEvent $event): void
    {
        $header = $this->getHeader($event->getMenu());

        $header
            ->addChild('lists', [
                'route' => 'setono_sylius_klaviyo_admin_member_list_index',
            ])
            ->setLabel('setono_sylius_klaviyo.menu.admin.main.marketing.member_lists')
            ->setLabelAttribute('icon', 'list alternate outline')
        ;
    }

    private function getHeader(ItemInterface $menu): ItemInterface
    {
        $header = $menu->getChild('marketing');
        if (null !== $header) {
            return $header;
        }

        return $menu;
    }
}
