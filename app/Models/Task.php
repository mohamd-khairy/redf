<?php

namespace App\Models;

use App\Enums\TaskTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'title', 'type', 'user_id', 'assigner_id', 'due_date', 'details', 'document_id', 'share_with', 'form_id',
    ];

    // public function getTypeAttribute($value)
    // {
    //     return TaskTypeEnum::all()[$value];
    // }
}