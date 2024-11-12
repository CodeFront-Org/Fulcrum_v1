<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name',100)->nullable();
            $table->string('middle_name',100)->nullable();
            $table->string('last_name',100)->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('gender')->nullable();
            $table->integer('id_number')->nullable()->unique();
            $table->string('pin_certificate')->nullable()->unique();
            $table->string('pin_certificate_photo')->nullable();
            $table->string('staff_number')->nullable();
            $table->string('employment_date')->nullable();
            $table->string('employment_type')->nullable();//contract or permanent
            $table->string('contract_end')->nullable();
            $table->string('contacts',50)->nullable()->unique();
            $table->string('mobile_login',50)->nullable()->unique();
            $table->string('alternative_contacts')->nullable();
            $table->integer('company_id')->nullable();
            $table->string('role_type')->default('user');
            $table->smallInteger('updated_psw')->nullable()->default(0);  // Indicate whether Active or Inactive
            $table->string('profile_pic_path')->nullable(); //to be used for img path
            $table->string('password');
            $table->timestamp('last_login')->nullable();
            $table->smallInteger('login_attempts')->default(0);
            $table->string('otp')->nullable();
            $table->timestamp('otp_expiry')->nullable();
            $table->tinyInteger('is_verified')->default(0);
            $table->tinyInteger('is_contract_expired')->default(0);
            $table->string('reset_code')->nullable();
            $table->string('reset_expiry')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}