<?php
namespace App\Enums;

enum FormRequestStatus: string
{
    case PENDING = 'PENDING';
    case ASSIGNED = 'ASSIGNED';
    case PROCESSING = 'PROCESSING';
    case CLOSED = 'CLOSED';
    // صدور حكم و  تحت التنفيذ و تحت المعالجه
}
