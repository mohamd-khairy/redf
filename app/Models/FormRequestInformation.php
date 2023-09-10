<?php

namespace App\Models;

use App\Enums\CourtTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormRequestInformation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'form_request_id', 'user_id', 'date_of_receipt', 'session_place', 'amount',
        'sessionDate', 'percentage', 'status', 'details', 'court', 'date', 'type'
    ];

    protected $casts = [
        'sessionDate' => 'datetime',
    ];

    public $with = ['user'];

    public function scopeLatestRecord($query)
    {
        return $query->latest()->first();
    }

    public function form_request()
    {
        return $this->belongsTo(FormRequest::class, 'form_request_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function session_place()
    {
        return $this->belongsTo(Branch::class, 'session_place');
    }

    // public function getCourtAttribute($value)
    // {
    //     return $value ? __('enums.' . $value) : null;
    // }
}
