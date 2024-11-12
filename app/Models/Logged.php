<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Logged extends Model
{
    use HasFactory,SoftDeletes;

    protected $table='login_attempts';
    protected $guarded=[];
}
