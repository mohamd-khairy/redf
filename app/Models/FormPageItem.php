<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormPageItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'label',
        'notes',
        'comment',
        'width',
        'height',
        'enabled',
        'required',
        'website_view',
        'childList',
        'form_page_id',
    ];
}
