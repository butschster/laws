<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClaimCalculatorController extends Controller
{
    /**
     * @param Request $request
     *
     * @return array
     */
    public function calculate(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0',
            'dates.start' => 'required|date',
            'dates.end' => 'required|date|after:date_of_borrowing',
            'is_interest_bearing_loan' => 'boolean',
            'interest_bearing_loan.interval' => ['required_if:is_interest_bearing_loan,true', Rule::in(['daily', 'weekly', 'monthly', 'yearly'])],
            'interest_bearing_loan.percent' => 'required_if:is_interest_bearing_loan,true|numeric|min:0|max:100',
            'partly_returned_money.*.date' => 'required|date|after:date_of_borrowing|before:date_of_return',
            'partly_returned_money.*.amount' => 'required|numeric|min:0',
        ]);

        $claim = new \App\Law\Claim(
            $data['amount'],
            Carbon::parse(array_get($data, 'dates.start')),
            Carbon::parse(array_get($data, 'dates.end')),
            array_get($data, 'interest_bearing_loan.percent'),
            array_get($data, 'interest_bearing_loan.interval')
        );

        foreach (array_get($data, 'partly_returned_money', []) as $row) {
            $claim->addReturnedMoney(Carbon::parse($row['date']), $row['amount']);
        }

        return [
            'total_percents' => $claim->calculateReturnPercentsAmount(),
            'total_amount' => $claim->calculateReturnAmount(),
        ];
    }
}