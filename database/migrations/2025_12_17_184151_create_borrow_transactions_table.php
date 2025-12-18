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
        Schema::create('borrow_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                ->constrained('students')
                ->restrictOnDelete();

            $table->foreignId('book_id')
                ->constrained('books')
                ->restrictOnDelete();

            $table->date('borrow_date');
            $table->date('due_date');
            $table->date('return_date')->nullable();

            $table->enum('status', ['borrowed', 'returned', 'overdue'])
                ->default('borrowed');

            $table->timestamps();

            // Indexes
            $table->index(['student_id', 'status'], 'idx_student_status');
            $table->index('due_date', 'idx_due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrow_transactions');
    }
};
