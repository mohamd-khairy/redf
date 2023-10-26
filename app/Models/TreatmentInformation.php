<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentInformation extends Model
{
    use HasFactory;
    protected $fillable = ['treatment_id', 'key', 'value', 'date'];

    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }
}
