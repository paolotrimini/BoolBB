<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apartments', function (Blueprint $table) {
            $table -> id();

            $table -> string('title', 256);
            $table -> string('cover_image');
            $table -> string('description');
            $table -> integer('rooms_number');
            $table -> integer('beds_number');
            $table -> integer('bathrooms_number');
            $table -> integer('area');
            $table -> string('address', 256);
            $table -> string('city');
            $table -> string('country');
            $table -> string('postal_code', 5);
            $table -> float('latitude', 8, 5);
            $table -> float('longitude', 8, 5);
            $table -> boolean('visible') -> default(0);

            $table -> softDeletes();
            $table -> bigInteger('user_id') -> unsigned() -> index();
            
            $table -> timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apartments');
    }
}
