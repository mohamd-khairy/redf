<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{

    use SoftDeletes , HasFactory;
    protected $fillable = ['name', 'status','priority','start_date','end_date','type', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
