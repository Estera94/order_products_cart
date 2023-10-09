<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
	/**
	 * Create invoice order
	 *
	 * @param $id
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function createInvoice($id)
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

		return response()->json(['message' => 'InvoiceController created successfully']);
	}
}
