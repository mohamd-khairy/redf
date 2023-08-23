<?php

namespace App\Models;

use App\Enums\CourtTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormRequestInformation extends Model
{
    use HasFactory , SoftDeletes;

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
