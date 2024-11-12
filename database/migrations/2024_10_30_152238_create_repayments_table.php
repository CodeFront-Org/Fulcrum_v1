<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repayments', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('loan_id');
            $table->integer('invoice_id')->nullable();
            $table->integer('company_id');
            $table->string('month');
            $table->string('year');
            $table->integer('loan_amount');
            $table->integer('installments');
            $table->integer('period');
            $table->tinyInteger('status')->default(0); //0 for unpaid  1 for paid   2 for partial payment
            $table->text('comments')->nullable();
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
        Schema::dropIfExists('repayments');
    }
}
