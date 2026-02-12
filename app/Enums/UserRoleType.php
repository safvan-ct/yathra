<?php
namespace App\Enums;

enum UserRoleType: string {
    case DEVELOPER       = 'developer';
    case SUPER_ADMIN     = 'super_admin';
    case AUTHORITY_ADMIN = 'authority_admin';
    case OPERATOR        = 'operator';
    case USER            = 'user';
}
