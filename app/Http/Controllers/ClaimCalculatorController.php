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
            'date_of_borrowing' => 'required|date_format:d.m.Y',
            'date_of_return' => 'required|date_format:d.m.Y|after:date_of_borrowing',
            'is_interest_bearing_loan' => 'boolean',
            'interest_bearing_loan.interval' => ['required_if:is_interest_bearing_loan,true', Rule::in(['daily', 'weekly', 'monthly', 'yearly'])],
            'interest_bearing_loan.percent' => 'required_if:is_interest_bearing_loan,true|numeric|min:0|max:100',
            'partly_returned_money.*.date' => 'required|date_format:d.m.Y|after:date_of_borrowing|before:date_of_return',
            'partly_returned_money.*.amount' => 'required|numeric|min:0',
        ]);

        $isPercent = (bool) $data['is_interest_bearing_loan'];
        $claim = new \App\Law\Claim(
            $data['amount'],
            custom_date($data['date_of_borrowing']),
            custom_date($data['date_of_return']),
            $isPercent ? array_get($data, 'interest_bearing_loan.percent') : 0,
            array_get($data, 'interest_bearing_loan.interval')
        );

        foreach (array_get($data, 'partly_returned_money', []) as $row) {
            $claim->addReturnedMoney(custom_date($row['date']), $row['amount']);
        }

        return [
            'total_percents' => $claim->calculateReturnPercentsAmount(),
            'total_amount' => $claim->calculateReturnAmount()
        ];
    }
}