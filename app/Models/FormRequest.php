<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class FormRequest extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'status',
        'message',
        'note',
        'form_id',
        'user_id'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(self::getFillable());
    }

    public function form_page_item_fill()
    {
        return $this->hasMany(FormPageItemFill::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function formAssignedRequests()
    {
        return $this->hasMany(FormAssignRequest::class)->whereNot('status','deleted');
    }
}
