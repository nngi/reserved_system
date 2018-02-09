<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('reserves');
        Schema::create('reserves', function(Blueprint $table) {
            $table->increments('reserve_id');
            $table->integer('facility_id');
            $table->integer('user_id');
            $table->dateTime('start_dt');
            $table->dateTime('end_dt');
            $table->string('overview');
            $table->text('detail')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reserves');
    }
}
