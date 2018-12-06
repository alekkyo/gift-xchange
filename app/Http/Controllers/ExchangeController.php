<?php

namespace App\Http\Controllers;

use App\Exchange;
use App\Http\Requests\NewExchangeRequest;

class ExchangeController extends Controller
{
    /**
     * Enter an exchange
     * @param NewExchangeRequest $request
     * @return \Redirect
     */
    public function newExchange(NewExchangeRequest $request)
    {
        if (!Exchange::where($request->code)->exists()) {
            Exchange::create([
                'code' => $request->code,
            ]);
        }
        return redirect('/exchange/' . $request->code);
    }

    /**
     * Show an exchange page
     * @param string $code
     * @return \View
     */
    public function showExchange($code)
    {
        $exchange = Exchange::where('code', $code)->first();
        if (empty($exchange)) {
            return view('welcome');
        }
        return view('exchange');
    }
}
