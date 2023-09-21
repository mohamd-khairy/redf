<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Treatment extends Model
{
    use HasFactory, SoftDeletes , LogsActivity;

    public $inPermission = true;

    protected $fillable = [
        'status',
        'type',
        'name',
        'date',
        'department_id',
        'user_id',
        'treatment_number',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(self::getFillable());
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'treatment_user', 'treatment_id', 'user_id');
    }
}
