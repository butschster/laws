<?php

namespace App\Http\Controllers;

use App\Law\Claim;
use App\Law\InterestRate;
use App\Law\Person;
use App\Law\Plaintiff;
use App\Law\Respondent;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class ClaimController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('claim.index');
    }

    public function send(Request $request)
    {
        $validator = $this->makeValidationRules($request);

        $validator->validate();
        $data = $validator->getData();

        $claim = new Claim(
            $data['amount'],
            custom_date($data['date_of_borrowing']),
            custom_date($data['date_of_return']),
            array_get($data, 'interest_bearing_loan.percent'),
            array_get($data, 'interest_bearing_loan.interval')
        );

        $claim->setParticipants(
            Plaintiff::fromArray($data['plaintiff']),
            Respondent::fromArray($data['respondent'])
        );

        $document = new Claim\ClaimDocument($claim);

        if ((bool) $data['has_claimed_money']) {
            foreach (array_get($data, 'claimed_money', []) as $row) {
                $claim->addClaimedMoney(custom_date($row['date']), $row['amount']);
            }
        }

        if ((bool)$data['has_partly_returned_money']) {
            foreach (array_get($data, 'partly_returned_money', []) as $row) {
                $claim->addReturnedMoney(custom_date($row['date']), $row['amount']);
            }
        }

        $forfeitType = array_get($data, 'forfeit.type');
        if ((bool) $data['has_forfeit'] && $forfeitType == 'fine') {
            $claim->setForfeit(array_get($data, 'forfeit.percent'), array_get($data, 'forfeit.interval'));
        }

        return [
            'link' => url($document->save('test.docx'))
        ];
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Validation\Validator
     */
    protected function makeValidationRules(Request $request): \Illuminate\Validation\Validator
    {
        $ruleIntervals = Rule::in(InterestRate::intervals());

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0',
            'basis_of_loan' => ['required', Rule::in(['voucher', 'contract'])],
            'date_of_borrowing' => 'required|date_format:d.m.Y|after_or_equal:date_of_signing',
            'date_of_return' => 'required|date_format:d.m.Y|after:date_of_borrowing',
            'date_of_signing' => 'required|date_format:d.m.Y',

            // Процентная ставка
            'is_interest_bearing_loan' => 'boolean',
            'interest_bearing_loan' => 'array',
            'interest_bearing_loan.interval' => ['required_if:is_interest_bearing_loan,true', $ruleIntervals],
            'interest_bearing_loan.percent' => 'required_if:is_interest_bearing_loan,true|numeric|min:0|max:100',

            'plaintiff.type' => ['required', Rule::in(Person::types())],
            'plaintiff.name' => 'required|fio',
            'plaintiff.address' => 'required|address',
            'plaintiff.ogrn' => 'required_unless:plaintiff.type,1|size:13',
            'plaintiff.has_fact_address' => 'boolean',

            'respondent.type' => ['required', Rule::in(Person::types())],
            'respondent.name' => 'required|fio',
            'respondent.address' => 'required|address',
            'respondent.ogrn' => 'required_unless:respondent.type,1|size:13',
            'respondent.has_fact_address' => 'boolean',

            // Частичное погашение займа
            'has_partly_returned_money' => 'required|boolean',
            'partly_returned_money' => 'array',

            // Дополнительные займы
            'has_claimed_money' => 'required|boolean',
            'claimed_money' => 'array',

            'has_forfeit' => 'boolean',
            'forfeit.type' => ['required_if:has_forfeit,true', Rule::in(['fine', 'mulct'])],
        ], [], trans('claim.fields'));

        $validator->sometimes('forfeit.fine.interval', ['required', $ruleIntervals], function ($input) {
            $data = $input->toArray();

            return (bool)array_get($data, 'has_forfeit', false) && array_get($data, 'forfeit.type') == 'fine';
        });

        $validator->sometimes('forfeit.fine.percent', 'required|numeric|min:0|max:100', function ($input) {
            $data = $input->toArray();

            return (bool)array_get($data, 'has_forfeit', false) && array_get($data, 'forfeit.type') == 'fine';
        });

        $validator->sometimes('forfeit.mulct', 'required|numeric|min:0', function ($input) {
            $data = $input->toArray();

            return (bool)array_get($data, 'has_forfeit', false) && array_get($data, 'forfeit.type') == 'mulct';
        });

        foreach (['respondent', 'plaintiff'] as $person) {
            $validator->sometimes($person.'.phone', 'phone', function ($input) use ($person) {
                $phone = array_get($input->toArray(), $person.'.phone');

                return !empty($phone);
            });

            $validator->sometimes($person.'.email', 'email', function ($input) use ($person) {
                $email = array_get($input->toArray(), $person.'.email');

                return !empty($email);
            });

            $validator->sometimes($person.'.fact_address', 'required|address', function ($input) use ($person) {
                return (bool)array_get($input->toArray(), $person.'.has_fact_address', false);
            });
        }

        foreach (['partly_returned_money', 'claimed_money'] as $additional) {
            $validator->sometimes($additional.'.*.date', 'required|date_format:d.m.Y|after:date_of_borrowing|before:date_of_return', function ($input) use ($additional) {
                return (bool)array_get($input->toArray(), 'has_'.$additional, false);
            });

            $validator->sometimes($additional.'.*.amount', 'required|numeric|min:0', function ($input) use ($additional) {
                return (bool)array_get($input->toArray(), 'has_'.$additional, false);
            });
        }

        return $validator;
    }
}
