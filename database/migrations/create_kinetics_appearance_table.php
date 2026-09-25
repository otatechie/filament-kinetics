<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// What the Appearance page saves, one row per panel.
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kinetics_appearance', function (Blueprint $table) {
            $table->string('panel')->primary();
            $table->json('settings');
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kinetics_appearance');
    }
};
