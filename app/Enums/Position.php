<?php

namespace App\Enums;

enum Position: string
{
    case FRONT_END = 'front-end';
    case BACK_END = 'back-end';
    case PM = 'pm';
    case DESIGNER = 'designer';
    case TESTER = 'tester';
}
