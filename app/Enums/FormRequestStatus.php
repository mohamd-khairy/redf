<?php

namespace App\Enums;

enum FormRequestStatus: string
{
    case ACCEPT = 'تم قبول الطلب';
    case RETURN = 'تم اعاده الطلب للمراجعه';
    case REFUSE = 'تم رفض الطلب';
}
