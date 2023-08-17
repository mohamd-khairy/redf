<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormRequestSide extends Model
{
    use HasFactory;
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