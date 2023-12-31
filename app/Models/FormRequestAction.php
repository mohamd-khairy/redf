<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormRequestAction extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'formable_type',
        'formable_id',
        'form_request_id',
        'msg'
    ];

    protected $with = ['formable'];
    
    public function formable()
    {
        return $this->morphTo();
    }
}
