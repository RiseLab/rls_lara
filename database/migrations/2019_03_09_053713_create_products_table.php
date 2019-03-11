<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_id');
            $table->string('brand');
            $table->string('title');
            $table->string('alias');
            $table->string('source_link');
            $table->string('info');
            $table->text('description');
            $table->text('photos');
            $table->unsignedDecimal('price', 8, 2);
            $table->unsignedDecimal('price_old', 8, 2);
	        $table->unsignedSmallInteger('stock');
	        $table->boolean('available');
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
        Schema::dropIfExists('products');
    }
}
