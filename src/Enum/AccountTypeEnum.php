<?php

declare(strict_types=1);

namespace App\Enum;

enum AccountTypeEnum: string
{
    case PEL = 'PEL';
    case PEE = 'PEE';
    case LIVRET = 'livret';
    case LIVRET_A = 'livret A';
    case LIVRET_JEUNE = 'livret jeune';
    case COMPTE_COURANT = 'compte courant';
}