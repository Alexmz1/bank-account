<?php

declare(strict_types=1);

namespace App\Enum;

enum AccountTypeEnum: string
{
    case PEL = 'PEL';
    case PEE = 'PEE';
    case LIVRET = 'Livret';
    case LIVRET_A = 'Livret A';
    case LIVRET_JEUNE = 'Livret jeune';
    case COMPTE_COURANT = 'Compte courant';
}