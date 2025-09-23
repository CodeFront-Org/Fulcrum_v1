<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Repayment extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded=[];

    //  protected $fillable = [
    //           'month', 'year',  'installments', 'status', 'comments'
    // ];
    protected $fillable = [
    'user_id', 'loan_id', 'company_id', 
    'loan_amount', 'installments', 
    'month', 'year', 'period','comments', 'status'
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