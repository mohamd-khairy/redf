<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
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
        'user_id',
        'form_request_number',
        'name'
    ];

    protected $with = ['user'];

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
        return $this->hasMany(FormAssignRequest::class)->whereNot('status', 'deleted');
    }

    public function formables()
    {
        return $this->hasMany(Formable::class);
    }

    public function formable()
    {
        return $this->hasOne(Formable::class)->with('forms');
    }

    public function formRequestActions()
    {
        return $this->morphMany(FormRequestAction::class, 'formable');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'form_request_id');
    }

    public function formRequestInformation()
    {
        return $this->hasOne(FormRequestInformation::class);
    }

    public function formRequestSide()
    {
        return $this->hasOne(FormRequestSide::class);
    }

    public function formRequestInformations()
    {
        return $this->hasMany(FormRequestInformation::class);
    }

    public function lastFormRequestInformation()
    {
        return $this->hasOne(FormRequestInformation::class)->orderBy('id', 'desc');
    }
}
