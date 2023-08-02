<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormPageItemFill extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'value',
        'review',
        'comment',
        'form_item_page_id',
        'user_id'
    ];
}
