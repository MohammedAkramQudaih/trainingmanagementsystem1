<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trainee_attendances', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedBigInteger('trainee_id');
            $table->enum('attendance_status', ['Present', 'Absent']);
            $table->text('comments')->nullable();
            $table->foreign('trainee_id')->references('id')->on('trainees')->onDelete('cascade');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainee_attendances');
    }
};
