<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingCartItems extends Model
{
	use HasFactory;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'product_id',
		'quantity',
	];

	public function shoppingCart()
	{
		return $this->belongsTo(ShoppingCart::class);
	}

	public function product()
	{
		return $this->belongsTo(Product::class);
	}
}
