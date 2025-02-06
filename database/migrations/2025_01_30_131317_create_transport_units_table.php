<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
 
public function up(){
    Schema::create('transport_units', function (Blueprint $table) {
        $table->id();
        $table->string('name');

        /* 
        Use enum instead of string here.
        String type, or simply keep it string,
        as the model ensures enum input.
        This would give some flexibility to the database.
        at cost of performance.
        */
         $table->string('type');
        $table->timestamps();
    });
    }
};