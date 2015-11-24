<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExtendAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attributes', function (Blueprint $table) {

            // String settings
            $table->integer('maxlength')->nullable();
            $table->string('pattern')->nullable();
            $table->string('autocomplete')->nullable();
            $table->string('placeholder')->nullable();

            // Numeric settings
            $table->double('min')->nullable();
            $table->double('max')->nullable();
            $table->double('step')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attributes', function (Blueprint $table) {
            $table->dropColumn([
                'maxlength', 'pattern', 'autocomplete', 'placeholder',
                'min', 'max', 'step',
            ]);
        });
    }
}
