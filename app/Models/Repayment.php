<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Repayment extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded=[];

     protected $fillable = [
              'month', 'year',  'installments', 'status', 'comments'
    ];


    public function company()
    {
        return $this->belongsTo(Company::class);
    }

        public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }


}