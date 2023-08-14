<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormInformation extends Model
{
    use HasFactory , SoftDeletes;
    protected $fillable = ['form_id', 'possibility', 'details'];

    // Define relationships if needed
    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
