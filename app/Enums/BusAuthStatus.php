<?php
namespace App\Enums;

enum BusAuthStatus: string {
    case PENDING   = 'pending';
    case APPROVED  = 'approved';
    case REJECTED  = 'rejected';
    case CANCELLED = 'cancelled';
}
