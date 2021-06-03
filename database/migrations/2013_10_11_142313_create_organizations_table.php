<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('city');
            $table->string('country')->default('Sri Lanka');
            //$table->unsignedBigInteger('type_id')->references('id')->on('organization_types');
            $table->text('description');
            $table->timestampsTz(); //time stamp with timezone in UTC
            $table->tinyInteger('status');
            $table->softDeletesTz('deleted_at', 0);
            $table->unsignedBigInteger('type_id')->nullable();  
            $table->foreign('type_id')->references('id')->on('organization_types')->onDelete('cascade');
            $table->unsignedBigInteger('branch_type_id')->nullable();  
            $table->foreign('branch_type_id')->references('id')->on('branch_types')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organizations');
    }
}