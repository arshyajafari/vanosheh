<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('customer_settlement', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('customer_id')->unsigned();
                $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
                $table->bigInteger('member_id')->unsigned();
                $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
                $table->string('payment_type', 50);
                $table->string('bank_title', 150)->nullable();
                $table->string('account_number', 50)->nullable();
                $table->timestamp('due_date')->nullable();
                $table->string('cheque_number', 50)->nullable();
                $table->string('cheque_status', 50)->nullable();
                $table->string('submit_number', 50)->nullable();
                $table->bigInteger('received_amount')->nullable();
                $table->integer('discount')->nullable();
                $table->bigInteger('amount');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::dropIfExists('customer_settlement');
        }
    };
