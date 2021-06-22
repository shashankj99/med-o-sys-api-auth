<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();

            $table->foreignId('district_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('name');

            $table->string('slug')
                ->unique();

            $table->string('nep_name')
                ->nullable()
                ->charset('utf8')
                ->collation('utf8_unicode_ci');

            $table->unsignedInteger('total_ward_no')
                ->default(0);

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
        Schema::dropIfExists('cities');
    }
}
