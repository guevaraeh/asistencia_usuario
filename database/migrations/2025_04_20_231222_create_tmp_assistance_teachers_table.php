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
        Schema::create('tmp_assistance_teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')/*->constrained()*/;
            //$table->foreignIdFor(User::class);
            $table->string('training_module', length: 100)->nullable();
            $table->string('period', length: 100)->nullable(); //$table->foreignId('period_id');
            $table->string('turn', length: 50)->nullable();
            $table->text('didactic_unit')->nullable();
            $table->dateTime('checkin_time', precision: 0)->nullable()->useCurrent();
            $table->dateTime('departure_time', precision: 0)->nullable()->useCurrent();
            $table->string('theme')->nullable();
            $table->string('place', length: 100)->nullable();
            $table->string('educational_platforms', length: 250)->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tmp_assistance_teachers');
    }
};
