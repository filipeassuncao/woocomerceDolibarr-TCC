<?php

namespace App\Http\Controllers;

use App\Jobs\ProductJob;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    /**
     * ProductController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @return array|string
     */
    public function create(Request $request)
    {
        dispatch(new ProductJob($request->header('x-wc-webhook-resource'), $request->all()));
    }
}
