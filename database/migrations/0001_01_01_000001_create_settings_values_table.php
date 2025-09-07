<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration {
    public function up(): void {
        Schema::create('settings_values', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->nullable()->index();
            $table->uuid('field_id')->index();
            $table->longText('value')->nullable();
            $table->timestamps();
            $table->unique(['tenant_id', 'field_id']);
            $table->foreign('field_id')->references('id')->on('settings_fields')->cascadeOnDelete();
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();
        });
    }
    public function down(): void {
        Schema::dropIfExists('settings_values');
    }
};