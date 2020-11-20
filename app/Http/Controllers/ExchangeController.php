<?php

namespace App\Http\Controllers;

use App\Exchange;
use App\Wish;
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
                'password' => $request->password,
                'participants' => $request->participants
            ]);
        }
        return redirect('/exchange/' . $request->code . ($request->filled('password') ? '?password=' . $request->password : ''));
    }

    /**
     * Show an exchange page
     * @param string $code
     * @param Request $request
     * @return \View
     */
    public function showExchange($code, Request $request)
    {
        /** @var Exchange $exchange */
        $exchange = Exchange::where('code', $code)->first();
        if (empty($exchange)) {
            return view('welcome');
        }

        if ($exchange->users()->exists()) {
            return view('view_exchange', ['code' => $code, 'users' => $exchange->users, 'password' => $request->password]);
        } else {
            return view('new_exchange', ['code' => $code, 'participants' => $exchange->participants]);
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

    /**
     * View picked user
     * @param string $code
     * @param string $link
     * @return \View
     */
    public function viewPicked($code, $link)
    {
        if ($exchange = Exchange::where('code', $code)->first()) {
            $user = User::where('exchange_id', $exchange->id)->where('link', $link)->first();
            if (!empty($user)) {
                return view('view_pick', ['code' => $code, 'user' => $user]);
            }
        }
        return view('welcome');
    }
    
    /**
     * View user wishlist
     * @param $userId
     * @return \View
     */
    function viewWishlist($code, $userId)
    {
      $user = User::find($userId);
      $wishes = Wish::where('user_id', $userId)->get();
      return view('wishlist', ['code' => $code, 'wishes' => $wishes, 'user' => $user]);
    }
    
    /**
     * Add new wish
     * @param $userId
     * @return \View
     */
    function addWish($code, $userId, Request $request)
    {
      if (!empty($request->wish) && !Wish::where('name', $request->wish)->exists()) {
        Wish::create([
          'user_id' => $userId,
          'name' => $request->wish,
        ]);
      }
      $user = User::find($userId);
      $wishes = Wish::where('user_id', $userId)->get();
      return view('wishlist', ['code' => $code, 'wishes' => $wishes, 'user' => $user]);
    }
    
    /**
     * View user wishlist
     * @param $userId
     * @return \View
     */
    function deleteWish($code, $userId, $wishId)
    {
      if ($wish = Wish::find($wishId)) {
        $wish->delete();
      }
      $user = User::find($userId);
      $wishes = Wish::where('user_id', $userId)->get();
      return view('wishlist', ['code' => $code, 'wishes' => $wishes, 'user' => $user]);
    }
}
