<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class document extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = ['name', 'status', 'type', 'user_id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(self::getFillable());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
