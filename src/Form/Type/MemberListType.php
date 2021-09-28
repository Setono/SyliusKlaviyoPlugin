<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Form\Type;

use Sylius\Bundle\ChannelBundle\Form\Type\ChannelChoiceType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class MemberListType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('klaviyoId', TextType::class, [
                'label' => 'setono_sylius_klaviyo.form.member_list.klaviyo_id',
                'disabled' => true,
                'required' => false,
            ])
            ->add('name', TextType::class, [
                'label' => 'setono_sylius_klaviyo.form.member_list.name',
                'disabled' => true,
                'required' => false,
            ])
            ->add('channels', ChannelChoiceType::class, [
                'multiple' => true,
                'expanded' => true,
                'label' => 'sylius.ui.channels',
                'required' => false,
            ])
        ;
    }
}
