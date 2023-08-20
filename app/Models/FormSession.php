<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormSession extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['form_request_id', 'date', 'status', 'details'];
    public function form_request()
    {
        return $this->belongsTo(FormRequest::class);
    }

}
