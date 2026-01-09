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
        Schema::create('monitors', function (Blueprint $table) {
            $table->id();
            $table->string('friendly_name');
            $table->string('url');
            $table->integer('type');
            $table->string('sub_type')->nullable();
            $table->integer('keyword_type')->default(0);
            $table->integer('keyword_case_type')->default(0);
            $table->string('keyword_value')->nullable();
            $table->string('http_username')->nullable();
            $table->string('http_password')->nullable();
            $table->string('port')->nullable();
            $table->integer('interval')->default(300);
            $table->integer('timeout')->default(30);
            $table->integer('status');
            $table->timestamp('create_datetime');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitors');
    }
};
