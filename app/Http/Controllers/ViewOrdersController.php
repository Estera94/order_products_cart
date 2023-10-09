<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\V1\BaseController;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;

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

    /**
     * Display user's orders
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Get the filter parameter from the request
        $filter = $request->get('order_status', 'all');

        // Define a base query
        $query = ShoppingCart::where('shopping_cart_status', 'complete')
            ->latest()
            ->with([
                'user' => function ($userQuery) {
                    $userQuery->select('id', 'name', 'email');
                },
                'shoppingCartItems'
            ]);

        // Apply filtering based on the 'filter' parameter
        if ($filter === 'invoiced') {
            $query->where('invoice_status', true);
        } elseif ($filter === 'not_invoiced') {
            $query->where('invoice_status', false);
        }

        // Paginate the results
        $shoppingCarts = $query->paginate(10);

        return response()->json($shoppingCarts);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request)
    {
        $order_id = $request->get('order_id');

        $order = ShoppingCart::where('id', $order_id)
            ->with([
                'user' => function ($userQuery) {
                    $userQuery->select('id', 'name', 'email');
                },
                'shoppingCartItems'
            ])
            ->first();

        return response()->json(['order' => $order]);
    }
}
