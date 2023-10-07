<?php

namespace App\Http\Controllers;

use App\Models\ShoppingCart;
use App\Models\ShoppingCartItems;
use Illuminate\Http\Request;

class ShoppingCartController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth:api');
	}

	/**
	 * Add item to cart.
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function addToCart(Request $request)
	{
		// Validate the request data
		$request->validate([
			'product_id' => 'required|integer',
			'quantity' => 'required|integer|min:1',
		]);

		$user = auth()->user();

		// Find or create the user's shopping cart
		$cart = $user->shoppingCart()->firstOrNew([]);

		$cart->save();

		$item = $this->checkProductIsInCart($cart, $request);

		return response()->json($item);
	}

	/**
	 *  Check if the product is already in the cart.
	 *
	 * @param $cart
	 * @param $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	private function checkProductIsInCart($cart, $request)
	{
		$existingItem = $cart->shoppingCartItems()->where('product_id', $request->product_id)->first();

		if ($existingItem) {
			// If the product is already in the cart, update the quantity
			$existingItem->update([
				'quantity' => $existingItem->quantity + $request->quantity,
			]);
		} else {
			// If the product is not in the cart, create a new item
			$cart->shoppingCartItems()->create([
				'product_id' => $request->product_id,
				'quantity' => $request->quantity,
			]);
		}

		$totalQuantity = $cart->shoppingCartItems()->sum('quantity');

		return response()->json([
			'item' => $existingItem,
			'total_quantity' => $totalQuantity,
		]);
	}
}
