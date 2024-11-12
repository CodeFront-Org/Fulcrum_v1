<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     * 
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('applicant_id')->nullable();
            $table->integer('company_id')->nullable();
            $table->string('contacts',50)->nullable();
            $table->string('alternative_contacts')->nullable();
            $table->string('kin')->nullable();//names for their next of kin
            $table->string('kin_contacts')->nullable();
            $table->integer('gross_salary')->nullable();
            $table->integer('net_salary')->nullable();
            $table->string('other_allowances')->nullable();
            $table->tinyText('outstanding_loan')->nullable();
            $table->string('outstanding_loan_org')->nullable();// name for the org that user has loan for
            $table->tinyText('outstanding_loan_balance')->nullable();
            $table->tinyText('requested_loan_amount')->nullable();
            $table->tinyText('payment_period')->nullable();// in months
            $table->tinyText('monthly_installments')->nullable();
            $table->integer('amount_payable')->nullable();
            $table->text('loan_reason')->nullable();
            $table->string('supporting_doc_file')->nullable();
            $table->string('file_name')->nullable();
            $table->tinyInteger('agreed_terms')->default(0);
            $table->tinyInteger('consent_to_irrevocable_authority')->default(0);
            $table->smallInteger('approval_level')->default(0);// to show who is supposed to approve.
            /**
             *  0 Loan has not yet been submitted thus no any approval actions required
             *  1 approval from HR required
             *  2 approval from Finance required
             *  3 approval from Admin required
             */
            $table->string('approver1_id')->nullable();
            $table->string('approver1_action')->nullable();//Reccomended or returned
            $table->text('approver1_comments')->nullable();
            $table->dateTime('approver1_date')->nullable();
            $table->string('approver2_id')->nullable();
            $table->string('approver2_action')->nullable();//Reccomended or returned
            $table->text('approver2_comments')->nullable();
            $table->dateTime('approver2_date')->nullable();
            $table->string('approver3_id')->nullable();
            $table->string('approver3_action')->nullable();//Reccomended or returned
            $table->text('approver3_comments')->nullable();
            $table->dateTime('approver3_date')->nullable();
            $table->tinyInteger('final_decision')->default(0);// To show whether the loan has been approved or not. 1 for approved, 2 for returned
            $table->string('mpesa_transaction')->nullable();
            $table->smallInteger('progress')->default(0);// Track loan step progress as outlined below.
            /**
             *  0 Loan was submitted and either approved or returned, which a value will be set in the final_decision column
             *  1 loan is in step one (Info details)
             *  2 loan is in step two (Salary details)
             *  3 loan is in step three (Loan details)
             *  4 loan is in step four (Term and declaration details)
             *  5 Loan has been submitted and awaiting approvals. after a decision is reached it will be set to zero(0)
             */
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
        Schema::dropIfExists('loans');
    }
}
