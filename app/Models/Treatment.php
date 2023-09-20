<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    use HasFactory;
    protected $fillable = [
        'status',
        'type',
        'name',
        'date',
        'department_id',
        'user_id',
        'treatment_number',
    ];

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
