<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Document extends Model
{

    use SoftDeletes ,LogsActivity, HasFactory;
    protected $fillable = ['name', 'status','priority','start_date','end_date','type', 'user_id'];




    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(self::getFillable());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function forms()
    {
        return $this->morphToMany(Form::class, 'formable');
    }
}
