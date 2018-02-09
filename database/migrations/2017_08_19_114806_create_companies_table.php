<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('companies');
        Schema::create('companies', function(Blueprint $table) {
            $table->increments('companys_id');
            $table->integer('agreement_id');
            $table->string('company_name');
            $table->string('zip_code');
            $table->string('prefecture_code');
            $table->string('city');
            $table->string('street_address');
            $table->string('tel');
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
        Schema::dropIfExists('companies');
    }
}
