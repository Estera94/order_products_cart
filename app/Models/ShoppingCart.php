<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
	use HasFactory;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'user_id',
	];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

	public function shoppingCartItems()
	{
		return $this->hasMany(ShoppingCartItems::class);
	}

	public function invoice()
	{
		return $this->hasOne(Invoice::class, 'id');
	}
}
