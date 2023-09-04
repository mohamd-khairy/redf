<?php

namespace App\Models;

use App\Enums\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
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
        'name',
        'branche_id',
        'form_type',
        'case_date'
    ];

    protected $with = ['user', 'lastFormRequestInformation'];

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

    public function request()
    {
        return $this->hasOne(Formable::class, 'form_request_id')->with('formable');
    }

    public function requests()
    {
        return $this->hasOne(Formable::class, 'formable_id');
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

    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branche_id');
    }

    // public function getStatusAttribute($value)
    // {
    //     return $value ? StatusEnum::$value() : $value;
    // }
}
