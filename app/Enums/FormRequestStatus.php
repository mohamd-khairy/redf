<?php
namespace App\Enums;

enum FormRequestStatus: string
{
    case PENDING = 'pending';
    // صدور حكم و  تحت التنفيذ و تحت المعالجه
}
