<?php

namespace App\Models;

use App\Enums\TaskTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Task extends Model
{
    use SoftDeletes, HasFactory;
    use LogsActivity;

    public $inPermission = true;

    protected $fillable = [
        'name', 'type', 'user_id', 'assigner_id', 'due_date', 'details', 'share_with', 'form_request_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(self::getFillable());
    }


    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigner_id');
    }

    // public function getTypeAttribute($value)
    // {
    //     return TaskTypeEnum::all()[$value];
    // }
}
