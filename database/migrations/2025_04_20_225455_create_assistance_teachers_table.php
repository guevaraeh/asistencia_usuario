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
        Schema::create('assistance_teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')/*->constrained()*/;
            //$table->foreignIdFor(User::class);
            $table->string('training_module', length: 100);
            $table->string('period', length: 100); //$table->foreignId('period_id');
            $table->string('turn', length: 50);
            $table->text('didactic_unit');
            $table->dateTime('checkin_time', precision: 0)->nullable()->useCurrent();
            $table->dateTime('departure_time', precision: 0)->nullable()->useCurrent();
            $table->string('theme');
            $table->string('place', length: 100);
            $table->string('educational_platforms', length: 250)->nullable();
            $table->text('remarks')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assistance_teachers');
    }
};
