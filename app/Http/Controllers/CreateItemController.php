<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CreateItemController extends Controller
{
    public function index()
    {
        return view('pages.create-item');
    }
}
