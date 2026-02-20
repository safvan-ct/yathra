<?php
namespace App\Enums;

enum OperatorAuthStatus: string {
    case PENDING   = 'pending';
    case APPROVED  = 'approved';
    case REJECTED  = 'rejected';
    case CANCELLED = 'cancelled';
}
