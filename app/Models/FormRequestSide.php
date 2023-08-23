<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FormRequestSide extends Model
{
    use HasFactory , SoftDeletes;
    protected $fillable = ['claimant_id', 'defendant_id','form_request_id']; // Define other fillable attributes

    public function formRequest()
    {
        return $this->belongsTo(FormRequest::class); // Assuming FormRequest model
    }

    public function claimant()
    {
        return $this->belongsTo(User::class, 'claimant_id');
    }

    public function defendant()
    {
        return $this->belongsTo(User::class, 'defendant_id');
    }
}
