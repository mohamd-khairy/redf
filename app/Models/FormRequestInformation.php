<?php

namespace App\Models;

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
}
