<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Calendar extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'calendarable_type',
        'calendarable_id',
        'date',
        'details',
        'user_id',
        'form_request_id'
    ];

    public function calendarable()
    {
        return $this->morphTo();
    }
}
