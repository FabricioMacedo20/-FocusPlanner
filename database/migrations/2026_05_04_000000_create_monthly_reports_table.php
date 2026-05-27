<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('monthly_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('month');
            $table->json('days_marked')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'year', 'month']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('monthly_reports');
    }
};
