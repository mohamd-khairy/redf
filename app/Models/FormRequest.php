<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'status',
        'message',
        'note',
        'form_id',
        'user_id'
    ];

    public function form_page_item_fill()
    {
        return $this->hasMany(FormPageItemFill::class);
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
