<?php

namespace App\Models;

use App\Enums\CourtTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormRequestInformation extends Model
{
    use HasFactory;

    protected $fillable = ['form_request_id', 'amount', 'percentage', 'status', 'details', 'court', 'date'];

    public function scopeLatestRecord($query)
    {
        return $query->latest()->first();
    }

    public function form_request()
    {
        return $this->belongsTo(FormRequest::class, 'form_request_id');
    }

    // public function getCourtAttribute($value)
    // {
    //     return $value ? __('enums.' . $value) : null;
    // }
}