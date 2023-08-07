<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormPage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'form_id'
    ];

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function items()
    {
        return $this->hasMany(FormPageItem::class, 'form_page_id');
    }
}