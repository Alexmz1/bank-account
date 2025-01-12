<?php

namespace App\Form;

use App\Entity\Account;
use App\Entity\PaymentMethod;
use App\Entity\Transaction;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Enum\TransactionTypeEnum;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount')
            ->add('operationType', ChoiceType::class, [
                'choices' => [
                    'Débit' => TransactionTypeEnum::DEBIT,
                    'Crédit' => TransactionTypeEnum::CREDIT,
                ],
                'choice_value' => fn (?TransactionTypeEnum $enum) => $enum?->value,
                'choice_label' => fn (TransactionTypeEnum $enum) => ucfirst($enum->value),
            ])
            ->add('description')
            ->add('paymentMethod', EntityType::class, [
                'class' => PaymentMethod::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
