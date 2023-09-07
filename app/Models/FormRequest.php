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
        'case_date',
        'case_type',
        'category',
        'specialization_id',
        'organization_id',
        'status_request'
    ];

    protected $appends = ['sub_status'];

    protected $with = ['user', 'lastFormRequestInformation', 'formRequestSide', 'branche'];

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

    public function formRequestActions()
    {
        return $this->morphMany(FormRequestAction::class, 'formable');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'form_request_id');
    }

    public function branche()
    {
        return $this->belongsTo(Branch::class, 'branche_id');
    }

    public function specialization()
    {
        return $this->belongsTo(Specialization::class, 'specialization_id');
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

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function getSubStatusAttribute()
    {
        return $this->formRequestInformations->where('sessionDate', '>=', now())->count() > 0 ? 'بإنتظار الجلسه' : null;
    }
}
