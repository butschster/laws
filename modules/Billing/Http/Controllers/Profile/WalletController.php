<?php


namespace Module\Billing\Http\Controllers\Profile;


use Module\Billing\Http\Controllers\Controller;

class WalletController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('profile.wallet.index', [
            'user' => \Auth::user(),
            'wallet' => \Auth::user()->wallet,
        ]);
    }
}