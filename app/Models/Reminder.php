<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = ['form_request_id', 'form_request_information_id', 'start_date', 'end_date'];
}
