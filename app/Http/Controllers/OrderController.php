<?php

namespace App\Http\Controllers;

use App\Jobs\CustomerJob;
use App\Jobs\ProductJob;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * OrderController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @return string
     */
    public function create(Request $request)
    {
        $resource = $request->header('x-wc-webhook-resource');

        dispatch(new CustomerJob($resource, $request->all()));
        dispatch(new ProductJob($resource, $request->all()));
    }
}
