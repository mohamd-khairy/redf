<?php
namespace App\Enums;

enum FormRequestStatus: string
{
    case PENDING = 'pending';
    case PROCCEING = 'procceing';
    // صدور حكم و  تحت التنفيذ و تحت المعالجه
}
