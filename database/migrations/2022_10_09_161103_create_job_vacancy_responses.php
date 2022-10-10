<?php

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
    public function up()
    {
        Schema::create('job_vacancy_responses', function (Blueprint $table) {
            $table->id();
            $table->string('content');
            $table->integer('job_vacancy_id')->references('id')->on('job_vacancies')->onDelete('CASCADE');
            $table->integer('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->timestamps();
            $table->timestamp('notify_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_vacancy_responses');
    }
};
