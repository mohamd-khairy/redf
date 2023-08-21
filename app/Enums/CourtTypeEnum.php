<?php

namespace App\Enums;

enum CourtTypeEnum: string
{
    case FIRST_DEGREE = 'first_degree';
    case APEAL = 'appeal';
    case SUPREME = 'supreme';
    case IMPLEMENTATION = 'implementation';
}
