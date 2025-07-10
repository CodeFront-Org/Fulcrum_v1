<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('month1')->nullable();
            $table->float('month2')->nullable();
            $table->float('month3')->nullable();
            $table->float('month4')->nullable();
            $table->float('month5')->nullable();
            $table->float('month6')->nullable();
            $table->float('month7')->nullable();
            $table->float('month8')->nullable();
            $table->float('month9')->nullable();
            $table->float('month10')->nullable();
            $table->float('month11')->nullable();
            $table->float('month12')->nullable();
            $table->tinyInteger('cut_off_day')->default(25); //Repayment cut-off day, default is 25th of the month
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
