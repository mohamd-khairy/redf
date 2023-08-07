<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organization extends Model
{
    use SoftDeletes, HasFactory;
    public $inPermission = true;

    protected $fillable = ['name', 'description'];
}
