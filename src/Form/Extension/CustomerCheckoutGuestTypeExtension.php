<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Form\Extension;

use Sylius\Bundle\CoreBundle\Form\Type\Customer\CustomerCheckoutGuestType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

final class CustomerCheckoutGuestTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('subscribedToNewsletter', CheckboxType::class, [
            'label' => 'sylius.form.customer.subscribed_to_newsletter',
            'required' => false,
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        yield CustomerCheckoutGuestType::class;
    }
}
