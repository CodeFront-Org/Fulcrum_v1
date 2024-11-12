<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Access extends Model
{
    //This model help track the company a user has access to 
    use HasFactory,SoftDeletes;
    protected $table='company_access';
    protected $guarded=[];
}
