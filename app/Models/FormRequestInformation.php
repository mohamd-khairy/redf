<?php

namespace App\Models;

use App\Enums\CaseTypeEnum;
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

    public $with = ['user'];

    public $appends = ['display_status'];

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

    public function getSessionDateAttribute($value)
    {
        return $value ? date('Y-m-d', strtotime($value)) : null;
    }

    public function getDisplayStatusAttribute()
    {
        try {
            return isset($this->attributes['status']) ? DisplayStatus($this->attributes['status']) : null;
        } catch (\Throwable $th) {
            return null;
        }
    }

    // public function getCourtAttribute($value)
    // {
    //     return $value ? __('enums.' . $value) : null;
    // }
}
