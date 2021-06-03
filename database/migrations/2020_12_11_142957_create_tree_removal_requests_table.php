<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreeRemovalRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tree_removal_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by_user_id');
            $table->string('description');
            $table->json('images');
            $table->json('governing_organizations');
            //$table->integer('province');
            //$table->integer('district');
            //$table->integer('gs_division');
            $table->double('land_size', 12,4);
            $table->string('land_size_unit');
            $table->json('special_approval')->nullable();// 1: EIA 2:SEA, 
            $table->integer('no_of_trees');
            $table->integer('no_of_tree_species');
            $table->integer('no_of_mammal_species');
            $table->integer('no_of_amphibian_species');
            $table->integer('no_of_reptile_species');
            $table->integer('no_of_avian_species');
            $table->text('species_special_notes');
            $table->integer('no_of_flora_species');
            //$table->integer('land_parcel_id');
            $table->json('tree_locations');
            $table->timestampsTz(); //time stamp with timezone in UTC
            //$table->tinyInteger('status');
            $table->softDeletesTz('deleted_at', 0);


            $table->unsignedBigInteger('land_parcel_id')->nullable();  
            $table->foreign('land_parcel_id')->references('id')->on('land_parcels')->onDelete('cascade');   

            $table->unsignedBigInteger('status_id')->nullable();
            $table->foreign('status_id')->references('id')->on('status')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tree_removal_requests');
    }
}
