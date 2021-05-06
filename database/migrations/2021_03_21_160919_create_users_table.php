<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('first_name');

            $table->string('middle_name')
                ->nullable();

            $table->string('last_name');

            $table->string('nep_name')
                ->nullable()
                ->charset('utf8')
                ->collation('utf8_unicode_ci');

            $table->string('province');

            $table->string('district');

            $table->string('city');

            $table->smallInteger('ward_no');

            $table->date('dob_ad');

            $table->string('dob_bs');

            $table->unsignedBigInteger('mobile');

            $table->string('email');

            $table->string('password');

            $table->tinyInteger('age')
                ->unsigned();

            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']);

            $table->string('img');

            $table->boolean('mobile_verification')
                ->default(false);

            $table->boolean('email_verification')
                ->default(false);

            $table->boolean('status')
                ->default(false);

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
        Schema::dropIfExists('users');
    }
}
