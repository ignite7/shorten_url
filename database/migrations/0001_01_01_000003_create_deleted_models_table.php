<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deleted_models', static function (Blueprint $table): void {
            $table->id();

            $table->string('key', 40);
            $table->string('model');
            $table->json('values');

            $table->timestamps();

            $table->unique(['model', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deleted_models');
    }
};
