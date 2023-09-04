<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formable extends Model
{
    use HasFactory;

    protected $table = "formable";

    protected $fillable = ['formable_type', 'formable_id', 'form_request_id'];

    public function formable()
    {
        return $this->morphTo();
    }

    public function formRequest()
    {
        return $this->belongsTo(FormRequest::class, 'form_request_id');
    }
}
