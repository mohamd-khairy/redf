<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentAction extends Model
{
    use HasFactory;

    protected $fillable = ['treatment_id' , 'user_id' , 'type' , 'text'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }
}
