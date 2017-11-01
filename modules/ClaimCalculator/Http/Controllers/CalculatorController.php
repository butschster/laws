<?php

namespace Module\ClaimCalculator\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Law\Claim;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Module\ClaimCalculator\Http\Resources\Calculator as CalculatorResource;

class CalculatorController extends Controller
{

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('ClaimCalculator::index');
    }

    /**
     * @param Request $request
     *
     * @return CalculatorResource
     */
    public function calculate(Request $request): CalculatorResource
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0',
            'date_of_borrowing' => 'required|date_format:d.m.Y',
            'date_of_return' => 'required|date_format:d.m.Y|after:date_of_borrowing',

            // Процентная ставка
            'is_interest_bearing_loan' => 'boolean',
            'interest_bearing_loan' => 'array',
            'interest_bearing_loan.interval' => ['required_if:is_interest_bearing_loan,true', Rule::in([Claim::DAILY, Claim::WEEKLY, Claim::MONTHLY, Claim::YEARLY])],
            'interest_bearing_loan.percent' => 'required_if:is_interest_bearing_loan,true|numeric|min:0|max:100',

            // Частичное погашение займа
            'has_returned_money' => 'required|boolean',
            'partly_returned_money' => 'array',
            'partly_returned_money.*.date' => 'required|date_format:d.m.Y|after:date_of_borrowing|before:date_of_return',
            'partly_returned_money.*.amount' => 'required|numeric|min:0',

            // Дополнительные займы
            'has_claimed_money' => 'required|boolean',
            'claimed_money' => 'array',
            'claimed_money.*.date' => 'required|date_format:d.m.Y|after:date_of_borrowing|before:date_of_return',
            'claimed_money.*.amount' => 'required|numeric|min:0',
        ], [], trans('claim.fields'));

        $isPercent = (bool) $data['is_interest_bearing_loan'];
        $claim = new \App\Law\Claim(
            $data['amount'],
            custom_date($data['date_of_borrowing']),
            custom_date($data['date_of_return']),
            $isPercent ? array_get($data, 'interest_bearing_loan.percent') : 0,
            array_get($data, 'interest_bearing_loan.interval')
        );

        if ((bool) $data['has_claimed_money']) {
            foreach (array_get($data, 'claimed_money', []) as $row) {
                $claim->addClaimedMoney(custom_date($row['date']), $row['amount']);
            }
        }

        if ((bool)$data['has_returned_money']) {
            foreach (array_get($data, 'partly_returned_money', []) as $row) {
                $claim->addReturnedMoney(custom_date($row['date']), $row['amount']);
            }
        }

        return new CalculatorResource($claim->calculate());
    }
}