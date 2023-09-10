<?php

namespace App\Enums;

use ArchTech\Enums\InvokableCases;

enum FormRequestStatus: string
{
    use InvokableCases;

    case ACCEPT = 'تم قبول الطلب';
    case RETURN = 'تم اعاده الطلب للمراجعه';
    case REFUSE = 'تم رفض الطلب';
}
