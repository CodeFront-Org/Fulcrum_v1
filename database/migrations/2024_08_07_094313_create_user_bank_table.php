<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBankTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_bank', function (Blueprint $table) {
            $table->id();
            $table->integer('bank_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('user_account_name')->nullable();//Name user is using in that account
            $table->string('account_number')->nullable();
            $table->string('branch')->nullable();
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
        Schema::dropIfExists('user_bank');
    }
}
