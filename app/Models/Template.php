<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Template extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivity;

    public $inPermission = true;

    protected $fillable = [
        'name',
        'user_id',
        'type',
        'icon'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(self::getFillable());
    }

    public function forms()
    {
        return $this->hasMany(Form::class);
    }

    function requests()
    {
        return $this->hasManyThrough(FormRequest::class, Form::class);
    }


}
