<?php
namespace App\Enums;

enum OperatorType: string {
    case PRIVATE       = 'private';
    case VILLAGE       = 'village';
    case CITY          = 'city';
    case STATE         = 'state';
    case NATIONAL      = 'national';
    case INTERNATIONAL = 'international';
}
