<?php

namespace App\Http\Controllers;

use App\Models\Product;
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
	 *  Display the products in the user's shopping cart.
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function index()
	{
		$user = auth()->user();

		// Find or create the user's shopping cart
		$cart = $user->shoppingCart()
			->where('shopping_cart_status', 'pending')
			->first();

		if ($cart->shopping_cart_status === 'pending') {
			// Retrieve the products in the shopping cart along with their details
			$cartItems = $cart->shoppingCartItems()
				->with('product')
				->with('product.category')
				->get();

			return response()->json([
				'products_in_cart' => $cartItems,
				'total_quantity' => $cart->shoppingCartItems()->sum('quantity')
			]);
		} else {
			return response()->json(['message' => 'Cart is complete']);
		}
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

		// Find shopping cart
		$cart = $user->shoppingCart()
			->where('shopping_cart_status', 'pending')
			->first();

		$item = $this->checkProductIsInCart($cart, $request);

		return response()->json($item);
	}

	public function completeOrder(Request $request)
	{
		$user = auth()->user();

		ShoppingCart::where('user_id', $user->id)
			->where('shopping_cart_status', 'pending')
			->update(['shopping_cart_status' => 'complete']);

		$newCart = new ShoppingCart([
			'user_id' => $user->id,
			'shopping_cart_status' => 'pending',
		]);

		$newCart->save();

		return response()->json(['message' => 'Cart status updated']);
	}

	public function deleteItem($id)
	{
		ShoppingCartItems::where('product_id', $id)->delete();

		return response()->json(['message' => 'Cart item deleted successfully']);
	}

	/**
	 *  Check if the product is already in the cart.
	 *
	 * @param $cart
	 * @param $request
	 * @param $operator
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	private function checkProductIsInCart($cart, $request)
	{
		$existingItem = $cart->shoppingCartItems()->where('product_id', $request->product_id)->first();
		$operator = $request->post('operator', 'increase');

		if ($existingItem) {
			// If the product is already in the cart, update the quantity
			if ($operator === 'increase') {
				$existingItem->update([
					'quantity' => $existingItem->quantity + $request->quantity,
				]);
			} else {
				$existingItem->update([
					'quantity' => $existingItem->quantity - $request->quantity,
				]);
			}
		} else {
			// If the product is not in the cart, create a new item
			$cart->shoppingCartItems()->create([
				'product_id' => $request->product_id,
				'quantity' => $request->quantity,
			]);
		}

		return response()->json([
			'total_quantity' => $cart->shoppingCartItems()->sum('quantity')
		]);
	}
}
