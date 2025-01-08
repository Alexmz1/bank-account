<?php

declare(strict_types=1);

namespace App\Enum;

enum RoleUserEnum: string
{
    case USER = 'user';
    case ADMIN = 'admin';
    case BANNED = 'banned';
}