<?php

namespace Module\Billing\Entities;

use Carbon\Carbon;

class Balance
{
    public function total()
    {
        return BalanceState::where('active_at', '<=', Carbon::now())
            ->orderBy('active_at')
            ->take(1)
            ->first()
            ->amount;
    }

    public function setState(float $amount, $date = null)
    {
        $date = $date instanceof Carbon ? $date : Carbon::now();

        return BalanceState::create([
            'amount' => $amount,
            'active_at' => $date,
        ]);
    }
}