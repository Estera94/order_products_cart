<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShoppingCartsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('shopping_carts', function (Blueprint $table) {
			$table->id();
			$table->unsignedInteger('user_id');
			$table->enum('shopping_cart_status', ['pending', 'complete'])->default('pending');
			$table->integer('shopping_cart_total')->nullable();
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
		Schema::dropIfExists('shopping_carts');
	}
}
