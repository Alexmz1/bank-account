<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Enum\AccountTypeEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('bankName')
            ->add('bankAccountNumber')
            ->add('iban')
            ->add('accountType', ChoiceType::class, [
                'choices' => [
                    'PEL' => AccountTypeEnum::PEL,
                    'PEE' => AccountTypeEnum::PEE,
                    'Livret' => AccountTypeEnum::LIVRET,
                    'Livret A' => AccountTypeEnum::LIVRET_A,
                    'Livret jeune' => AccountTypeEnum::LIVRET_JEUNE,
                    'Compte courant' => AccountTypeEnum::COMPTE_COURANT,
                ],
                'choice_value' => fn (?AccountTypeEnum $enum) => $enum?->value,
                'choice_label' => fn (AccountTypeEnum $enum) => ucfirst($enum->value),
            ])
            ->add('bankBalance')
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
                'attr' => ['style' => 'display:none;'],
                'label' => false,
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
        ]);
    }
}
