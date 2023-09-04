<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;

enum StatusEnum: string
{
    use InvokableCases;

    case PENDING = 'بانتظار الحكم الإبتدائي';
    case WAIT_SECOND_RULE = 'بانتظار حكم الإستئناف';
    case WAIT_THIRD_RULE = 'بانتظار الحكم النهائي';
    case ASSIGNED = 'لدي الموظف المختص';
}
