<?php

namespace App\Http\Controllers;

use App\Exchange;
use App\Http\Requests\NewExchangeRequest;
use App\User;
use Illuminate\Http\Request;

class ExchangeController extends Controller
{
    const LINKS = [
        'Santa',
        'Reindeer',
        'Present',
        'Christmas',
        'Turkey',
        'Frosty',
        'NewYear',
        'Family',
        'Friends',
        'Tree',
        'Snow',
        'Winter',
        'Mistletoe',
        'Star',
        'Lights',
        'Toys',
        'Fruitcake',
        'Angel',
        'Elves',
        'Party',
    ];

    /**
     * Enter an exchange
     * @param NewExchangeRequest $request
     * @return \Redirect
     */
    public function newExchange(NewExchangeRequest $request)
    {
        if (!Exchange::where('code', $request->code)->exists()) {
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

        if ($exchange->users()->exists()) {
            return view('view_exchange', ['code' => $code, 'users' => $exchange->users]);
        } else {
            return view('new_exchange', ['code' => $code]);
        }
    }

    /**
     * Create exchange users
     * @param string $code
     * @param Request $request
     * @return \View
     */
    public function createExchangeUsers($code, Request $request)
    {
        /** @var Exchange $exchange */
        $exchange = Exchange::where('code', $code)->first();
        if (empty($exchange) || $exchange->users()->exists()) {
            return view('welcome');
        }

        foreach ($request->all() as $index => $name) {
            if (!empty($name) && $index !== '_token') {
                User::create([
                    'exchange_id' => $exchange->id,
                    'link' => self::LINKS[$index],
                    'name' => $name,
                ]);
            }
        }

        $originalUsers = $exchange->users()->get()->toArray();
        $names = $exchange->users()->pluck('name')->toArray();
        do {
            $isValid = true;
            shuffle($names);
            foreach ($names as $index => $name) {
                if ($originalUsers[$index]['name'] === $name) {
                    $isValid = false;
                    break;
                }
            }
        } while (!$isValid);

        foreach ($originalUsers as $index => $originalUser) {
            User::where('id', $originalUser['id'])->update([
                'name_picked' => $names[$index]
            ]);
        }

        return redirect('/exchange/' . $code);
    }
}
