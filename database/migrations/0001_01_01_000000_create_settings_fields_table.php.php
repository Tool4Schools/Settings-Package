<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('settings_fields', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('namespace');
            $table->string('name');
            $table->string('type')->default('string');
            $table->longText('default')->nullable();
            $table->json('options')->nullable();
            $table->longText('description')->nullable();
            $table->json('meta')->nullable();
            $table->boolean('secure')->default(false);
            $table->timestamps();
            $table->unique(['namespace', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings_fields');
    }
};
