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

    protected static function boot()
    {
        parent::boot();
        // Add your code here
        static::created(function ($model) {
            $code = rand(100000, 999999);
            $model->form_request_number = $code;
            $model->name = $model->form->name . " ($code)";
            $model->save();
        });
    }

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
        return $this->morphMany(Formable::class, 'formable');
    }

    public function formRequestActions()
    {
        return $this->morphMany(FormRequestAction::class, 'formable');
    }
}
