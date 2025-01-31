<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTypeColumnInTransportUnits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transport_units', function (Blueprint $table) {
            // Change 'type' column to string
            $table->string('type')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transport_units', function (Blueprint $table) {
            // Optionally reverse the change (e.g. change back to integer)
            $table->varchar('type')->change();
        });
    }
}

