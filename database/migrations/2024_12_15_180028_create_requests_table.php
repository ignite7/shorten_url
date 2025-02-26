<?php

declare(strict_types=1);

use App\Enums\HttpMethod;
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
        Schema::create('requests', static function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('url_id')->constrained();
            $table->foreignUlid('user_id')->nullable()->constrained();
            $table->uuid('anonymous_token')->nullable()->default(null)->index();
            $table->enum('method', HttpMethod::values())->index();
            $table->longText('uri')->index();
            $table->json('query')->default(collect());
            $table->json('headers')->default(collect());
            $table->json('body')->default(collect());
            $table->ipAddress()->index();
            $table->longText('user_agent')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
