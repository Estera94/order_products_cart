<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;

class InvoiceController extends Controller
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
     *  Display order's invoice.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $invoices = Invoice::all();
        $invoiceDetails = [];

        foreach ($invoices as $invoice) {
            $shoppingCart = ShoppingCart::find($invoice->shopping_cart_id);

            if ($shoppingCart) {
                $shoppingCart->load([
                    'user' => function ($userQuery) {
                        $userQuery->select('id', 'name', 'email');
                    },
                    'shoppingCartItems.product' => function ($productQuery) {
                        $productQuery->select('id', 'name', 'description', 'photo', 'price');
                    },
                ]);

                $invoiceDetails[] = $shoppingCart;
            }
        }

        return response()->json(['data' => $invoiceDetails]);
    }

    /**
     * Create invoice order
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create($id)
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

    /**
     * Show invoice details
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $order_id = $request->get('invoice_id');

        $order = ShoppingCart::where('id', $order_id)
            ->with([
                'user' => function ($userQuery) {
                    $userQuery->select('id', 'name', 'email');
                },
                'shoppingCartItems.product' => function ($productQuery) {
                    $productQuery->select('id', 'name', 'description', 'photo', 'price');
                },
            ])
            ->first();

        return response()->json(['data' => $order]);
    }
}
