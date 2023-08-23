<?php
namespace App\Enums;

enum FormRequestStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    // صدور حكم و  تحت التنفيذ و تحت المعالجه
}
