<?php

namespace App\Http\Controllers;

use App\Jobs\CustomerJob;
use Illuminate\Http\Request;


class CustomerController extends Controller
{
    /**
     * CustomerController constructor.
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
        dispatch(new CustomerJob($request->header('x-wc-webhook-resource'), $request->all()));
    }
}


