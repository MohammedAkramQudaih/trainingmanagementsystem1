<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stored_files', function (Blueprint $table) {
            $table->id();
            $table->string('fileName');

            $table->string('fileUrl')->unique();
            $table->string('fileType');
            $table->integer('fileSize');
            $table->unsignedBigInteger('trainee_id')->nullable();
            $table->unsignedBigInteger('program_id')->nullable();
//            $table->foreign('user_id')->references('id')->on('users');
            $table->string('notes');
            $table->softDeletes();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stored_files');
    }
};
