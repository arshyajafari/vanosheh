<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        /**
         * Run the migrations.
         */
        public function up(): void {
            Schema::create('message_member_pivot', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('message_id');
                $table->foreign('message_id')->references('id')->on('messages')->onDelete('cascade');
                $table->bigInteger('member_id');
                $table->foreign('member_id')->references('id')->on('members')->onDelete('cascade');
                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void {
            Schema::dropIfExists('message_member_pivot');
        }
    };
