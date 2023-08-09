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
        'form_page_item_id',
        'user_id',
        'form_request_id'
    ];

    public function form_requests()
    {
        return $this->belongsTo(FormRequest::class);
    }

    public function page_item()
    {
        return $this->belongsTo(FormPageItem::class);
    }

}
