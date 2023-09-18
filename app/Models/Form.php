<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Form extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'name',
        'description',
        'user_id',
        'template_id',
        'main'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(self::getFillable());
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function pages()
    {
        return $this->hasMany(FormPage::class);
    }

    public function form_requests()
    {
        return $this->hasMany(FormRequest::class);
    }
    public function formables()
    {
        return $this->morphMany(Formable::class, 'formable');
    }

    public function formRequestActions()
    {
        return $this->morphMany(FormRequestAction::class, 'formable');
    }

    public function formRequests()
    {
        return $this->hasMany(FormRequest::class);
    }

    public function tasks()
    {
        return $this->hasManyThrough(Task::class, FormRequest::class);
    }

    public function stages()
    {
        return $this->belongsToMany(Stage::class, 'stage_forms', 'form_id', 'stage_id')->withPivot('order');
    }
}
