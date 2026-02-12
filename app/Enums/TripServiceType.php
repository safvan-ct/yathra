<?php
namespace App\Enums;

enum TripServiceType: string {
    case ORDINARY   = 'ordinary';
    case LIMITED    = 'limited';
    case FAST       = 'fast';
    case SUPER_FAST = 'super_fast';
    case EXPRESS    = 'express';
}
