<?php

declare(strict_types=1);

namespace App\Enum;

enum TransactionTypeEnum: string
{
    case CREDIT = 'credit';
    case DEBIT = 'debit';
}