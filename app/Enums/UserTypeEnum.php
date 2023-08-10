<?php

namespace App\Enums;

enum UserTypeEnum: string
{
    case USER = 'user';
    case EMPLOYEE = 'employee';
    case COMPANY = 'company';
    case GOVERNORATE = 'governorate';
}
