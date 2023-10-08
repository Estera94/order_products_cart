<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\V1\BaseController;
use App\Models\Invoice;
use App\Models\ShoppingCart;
use App\Models\User;

class ViewOrdersController extends BaseController
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

	public function index()
	{
		$shoppingCarts = shoppingCart::where('shopping_cart_status', 'complete')
			->with([
				'user' => function ($userQuery) {
					$userQuery->select('id', 'name', 'email');
				}, 'shoppingCartItems'
			])
			->get();

		return response()->json($shoppingCarts);
	}

	public function addInvoice($id)
	{
		$invoice = new Invoice([
			'shopping_cart_id' => $id,
		]);

		// Save the invoice to the database.
		$invoice->save();

		// Update the shopping cart's invoice_status to true.
		ShoppingCart::where('id', $id)
			->where('invoice_status', false)
			->update([
				'invoice_status' => true,
			]);

		return response()->json(['message' => 'Invoice created successfully']);
	}
}
