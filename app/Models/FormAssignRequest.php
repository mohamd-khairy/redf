<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormAssignRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'form_request_id',
        'assigner_id',
        'date',
        'type',
        'status'
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'created_at' => "datetime:Y-m-d H:i",
    ];

    protected $with = ['user', 'assigner'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigner_id');
    }

    public function formRequest()
    {
        return $this->belongsTo(FormRequest::class);
    }
}
