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
        Schema::create('email_email_type', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\ManagerEmails::class)->index()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\EmailType::class)->index()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_email_type');
    }
};
