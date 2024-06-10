<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToJobs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->string('job_type')->nullable();
            $table->text('pick_up_location')->nullable();
            $table->text('drop_off_location')->nullable();
            $table->string('job_price')->nullable();
            $table->string('price_per_hour')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jobs', function (Blueprint $table) {
            Schema::dropIfExists('job_type');
            Schema::dropIfExists('pick_up_location');
            Schema::dropIfExists('drop_off_location');
            Schema::dropIfExists('job_price');
            Schema::dropIfExists('price_per_hour');
        });
    }
}
