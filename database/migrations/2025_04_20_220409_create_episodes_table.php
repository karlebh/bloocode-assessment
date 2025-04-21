<?php

use App\Models\Podcast;
use App\Models\User;
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
        Schema::create('episodes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Podcast::class);
            $table->string('title');
            $table->longText('description');
            $table->string('slug')->unique();
            $table->string('audio_url');
            $table->time('duration')->nullable();
            $table->integer('episode_number')->nullable();
            $table->text('summary')->nullable();
            $table->date('release_date')->nullable();
            $table->integer('listen_count')->default(0);
            $table->timestamps();

            $table->index(['title', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('episodes');
    }
};
