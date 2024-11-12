<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number');
            $table->integer('company_id');
            $table->integer('staff_id');//Person who marked it paid or Unpaid or Partial
            $table->integer('loan_requests')->nullable();
            $table->integer('loan_amount');
            $table->integer('interest_amount')->nullable();
            $table->integer('payable_amount');
            $table->integer('amount_paid')->default(0);
            $table->tinyInteger('status')->default(0); // 1 paid 0 unpaid 2 partially paid
            $table->date('invoice_date')->nullable();
            $table->date('date_paid')->nullable();
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
        Schema::dropIfExists('invoices');
    }
}
