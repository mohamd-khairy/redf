<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formable extends Model
{
    use HasFactory;
    protected $fillable = ['formable_type', 'formable_id','form_id'];

    public function forms()
    {
        return $this->morphToMany(Form::class, 'formable');
    }
}
