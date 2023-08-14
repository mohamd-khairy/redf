<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormSession extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['form_id', 'date', 'status', 'details'];
    public function form()
    {
        return $this->belongsTo(Form::class);
    }

}
