<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributeProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_product', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('product_id');
            $table->unsignedInteger('attribute_id');
            $table->string('value');

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('attribute_id')->references('id')->on('attributes');

            $table->unique(['product_id', 'attribute_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('attribute_product');
    }
}
