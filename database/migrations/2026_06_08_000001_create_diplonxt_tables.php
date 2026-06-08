<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('theses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->unique()->constrained('users')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('professor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('description');
            $table->string('status')->default('eligibility_check')->index();
            $table->unsignedTinyInteger('progress_percentage')->default(5);
            $table->string('current_phase')->default('Eligibility Check');
            $table->date('deadline')->nullable();
            $table->dateTime('defense_date')->nullable();
            $table->boolean('is_archived')->default(false)->index();
            $table->timestamps();
        });

        Schema::create('thesis_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thesis_id')->constrained()->cascadeOnDelete();
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('version_number');
            $table->string('file_path');
            $table->string('original_file_name');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('status')->default('pending')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['thesis_id', 'version_number']);
        });

        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('professor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('thesis_id')->constrained()->cascadeOnDelete();
            $table->foreignId('thesis_version_id')->constrained()->cascadeOnDelete();
            $table->text('comment');
            $table->text('student_reply')->nullable();
            $table->string('status')->default('open')->index();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
        });

        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('receiver_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('thesis_id')->constrained()->cascadeOnDelete();
            $table->text('message');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->index(['receiver_id', 'read_at']);
        });

        Schema::create('defense_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thesis_id')->unique()->constrained()->cascadeOnDelete();
            $table->dateTime('scheduled_at');
            $table->string('location');
            $table->json('committee_members')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('thesis_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thesis_id')->constrained()->cascadeOnDelete();
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('from_status')->nullable();
            $table->string('to_status');
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('thesis_status_histories');
        Schema::dropIfExists('defense_schedules');
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('feedback');
        Schema::dropIfExists('thesis_versions');
        Schema::dropIfExists('theses');
    }
};
