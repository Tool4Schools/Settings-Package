<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings_values', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('field_id')->index();
            $table->longText('value')->nullable();
            $table->timestamps();
            $table->foreign('field_id')->references('id')->on('settings_fields')->cascadeOnDelete();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('settings_values');
    }
};
