<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'path', 'fileable_type', 'fileable_id', 'priority', 'start_date', 'end_date', 'type', 'user_id', 'status'];

    public function fileable()
    {
        return $this->morphTo();
    }

    public function getPathAttribute($value)
    {
        if ($value) {
            return url('storage/' . $value);
        }

        return null;
    }
}
