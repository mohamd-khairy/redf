<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormSession extends Model
{
    use HasFactory;
    protected $fillable = ['form_id', 'date', 'status', 'details'];
    public function form()
    {
        return $this->belongsTo(Form::class);
    }

}
