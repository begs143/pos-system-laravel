<?php

namespace App\Http\Controllers;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        return view('pages.purchase-orders.index');
    }
}
