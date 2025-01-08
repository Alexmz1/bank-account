<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Enum\RoleUserEnum;
use App\Entity\User;
use App\Entity\Account;
use App\Enum\AccountTypeEnum;
use App\Enum\TransactionTypeEnum;
use App\Entity\PaymentMethod;
use App\Entity\Transaction;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // User
        $user = new User();
        $user->setFirstName('John')
             ->setLastName('Doe')
             ->setEmail('john.doe@example.com')
             ->setPassword(password_hash('password', PASSWORD_BCRYPT))
             ->setRole(RoleUserEnum::USER)
             ->setCreatedAt(new \DateTimeImmutable())
             ->setUpdatedAt(new \DateTime());
        $manager->persist($user);

        // Admin
        $admin = new User();
        $admin->setFirstName('Jane')
              ->setLastName('Doe')
              ->setEmail('jane.doe@example.com')
              ->setPassword(password_hash('password', PASSWORD_BCRYPT))
              ->setRole(RoleUserEnum::ADMIN)
              ->setCreatedAt(new \DateTimeImmutable())
              ->setUpdatedAt(new \DateTime());
        $manager->persist($admin);

        // Banned user
        $bannedUser = new User();
        $bannedUser->setFirstName('Banned')
                   ->setLastName('User')
                   ->setEmail('banned.user@example.com')
                   ->setPassword(password_hash('password', PASSWORD_BCRYPT))
                   ->setRole(RoleUserEnum::BANNED)
                   ->setCreatedAt(new \DateTimeImmutable())
                   ->setUpdatedAt(new \DateTime());
        $manager->persist($bannedUser);

        // Bank account for user
        $account = new Account();
        $account->setFirstName($user->getFirstName())
                ->setLastName($user->getLastName())
                ->setBankName('BNP Paribas')
                ->setIban('FR1420041010050500013M02606')
                ->setBankAccountNumber('1234567890')
                ->setAccountType(AccountTypeEnum::COMPTE_COURANT)
                ->setBankBalance(20000)
                ->setUser($user)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUpdatedAt(new \DateTime());
        $manager->persist($account);

        // Payment methods
        $paymentMethodVisa = new PaymentMethod();
        $paymentMethodVisa->setName('Visa')
                          ->setUser($user)
                          ->setCreatedAt(new \DateTimeImmutable())
                          ->setUpdatedAt(new \DateTime());
        $manager->persist($paymentMethodVisa);

        $paymentMethodBankTransfer = new PaymentMethod();
        $paymentMethodBankTransfer->setName('Virement bancaire')
                                  ->setUser($user)
                                  ->setCreatedAt(new \DateTimeImmutable())
                                  ->setUpdatedAt(new \DateTime());
        $manager->persist($paymentMethodBankTransfer);

        // Transactions
        for ($i = 0; $i < 3; $i++) {
            $transaction = new Transaction();
            $transaction->setPoint(true)
                        ->setAmount(mt_rand(100, 1000) / 10)
                        ->setOperationType(TransactionTypeEnum::DEBIT)
                        ->setPaymentMethod($paymentMethodVisa)
                        ->setDescription('Transaction de test Débit ' . ($i + 1))
                        ->setAccount($account)
                        ->setCreatedAt(new \DateTimeImmutable())
                        ->setUpdatedAt(new \DateTime());
            $manager->persist($transaction);
        }

        for ($i = 0; $i < 2; $i++) {
            $transaction = new Transaction();
            $transaction->setPoint(false)
                        ->setAmount(mt_rand(100, 1000) / 10)
                        ->setOperationType((TransactionTypeEnum::CREDIT))
                        ->setPaymentMethod($paymentMethodBankTransfer)
                        ->setDescription('Transaction de test Crédit ' . ($i + 1))
                        ->setAccount($account)
                        ->setCreatedAt(new \DateTimeImmutable())
                        ->setUpdatedAt(new \DateTime());
            $manager->persist($transaction);
        }

        $manager->flush();
    }
}
