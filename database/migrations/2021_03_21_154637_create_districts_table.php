<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('districts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('province_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('name');

            $table->string('slug')
                ->unique();

            $table->string('nep_name')
                ->nullable()
                ->charset('utf8')
                ->collation('utf8_unicode_ci');

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
        Schema::dropIfExists('districts');
    }
}
