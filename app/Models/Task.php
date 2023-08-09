<?php

namespace App\Models;

use App\Enums\TaskTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Task extends Model
{
    use HasFactory;
    use LogsActivity;

    public $inPermission = true;

    protected $fillable = [
        'title', 'type', 'user_id', 'assigner_id', 'due_date', 'details', 'document_id', 'share_with', 'form_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(self::getFillable());
    }

    // public function getTypeAttribute($value)
    // {
    //     return TaskTypeEnum::all()[$value];
    // }
}
