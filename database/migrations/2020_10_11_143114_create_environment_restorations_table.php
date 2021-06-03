<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnvironmentRestorationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('environment_restorations', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('created_by_user_id');
            
            $table->timestampTz('approval_at')->nullable(); //null-pending, approved/ rejected time
            $table->timestampsTz(); //time stamp with timezone in UTC
            $table->tinyInteger('status');
            $table->softDeletesTz('deleted_at', 0);


            // Connecting the environment restoration to the organizations and designation tables
            $table->unsignedBigInteger('environment_restoration_activity_id');
            $table->unsignedBigInteger('eco_system_id');
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('land_parcel_id');
            
            /*
                {[species_id, count, remarks, amendments, amended_by, amended_on]}
            */
            $table->foreign('environment_restoration_activity_id','era_id_foreign')->references('id')->on('environment_restoration_activities')->onDelete('cascade');
            $table->foreign('eco_system_id')->references('id')->on('ecosystems_types')->onDelete('cascade');
            $table->foreign('organization_id')->references('id')->on('organizations')->onDelete('cascade');
            $table->foreign('land_parcel_id')->references('id')->on('land_parcels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('environment_restorations');
    }
}
