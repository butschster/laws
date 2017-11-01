<?php

namespace App\Http\Controllers;

use App\Law\Claim;
use App\Law\Person;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use PhpOffice\PhpWord\PhpWord;
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

    public function send(Request $request, PhpWord $phpWord)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0',
            'basis_of_loan' => ['required', Rule::in(['voucher', 'contract'])],
            'date_of_borrowing' => 'required|date_format:d.m.Y|after_or_equal:date_of_signing',
            'date_of_return' => 'required|date_format:d.m.Y|after:date_of_borrowing',
            'date_of_signing' => 'required|date_format:d.m.Y',

            'has_forfeit' => 'boolean',
            'forfeit.type' => ['required_if:has_forfeit,true', Rule::in(['fine', 'mulct'])],
            'forfeit.mulct' => 'required_if:forfeit.type,mulct|numeric|min:0',
            'forfeit.fine.interval' => ['required_if:forfeit.type,fine', Rule::in(['daily', 'weekly', 'monthly', 'yearly'])],
            'forfeit.fine.percent' => 'required_if:forfeit.type,fine|numeric|min:0|max:100',

            // Процентная ставка
            'is_interest_bearing_loan' => 'boolean',
            'interest_bearing_loan' => 'array',
            'interest_bearing_loan.interval' => ['required_if:is_interest_bearing_loan,true', Rule::in([
                Claim::DAILY, Claim::WEEKLY, Claim::MONTHLY, Claim::YEARLY
            ])],
            'interest_bearing_loan.percent' => 'required_if:is_interest_bearing_loan,true|numeric|min:0|max:100',

            'plaintiff.type' => ['required', Rule::in(Person::types())],
            'plaintiff.name' => 'required|fio',
            'plaintiff.address' => 'required|address',
            'plaintiff.has_fact_address' => 'boolean',

            'respondent.type' => ['required', Rule::in(Person::types())],
            'respondent.name' => 'required|fio',
            'respondent.address' => 'required|address',
            'respondent.has_fact_address' => 'boolean',

            // Частичное погашение займа
            'has_partly_returned_money' => 'required|boolean',
            'partly_returned_money' => 'array',

            // Дополнительные займы
            'has_claimed_money' => 'required|boolean',
            'claimed_money' => 'array',
        ], [], trans('claim.fields'));

        foreach (['respondent', 'plaintiff'] as $person) {
            $validator->sometimes($person.'.phone', 'phone', function ($input) use ($person) {
                $phone = array_get($input->toArray(), $person.'.phone');

                return !empty($phone);
            });

            $validator->sometimes($person.'.fact_address', 'required|address', function ($input) use ($person) {
                return (bool) array_get($input->toArray(), $person.'.has_fact_address', false);
            });
        }

        foreach (['partly_returned_money', 'claimed_money'] as $additional) {
            $validator->sometimes($additional.'.*.date', 'required|date_format:d.m.Y|after:date_of_borrowing|before:date_of_return', function ($input) use ($additional) {
                return (bool) array_get($input->toArray(), 'has_'.$additional, false);
            });

            $validator->sometimes($additional.'.*.amount', 'required|numeric|min:0', function ($input) use ($additional) {
                return (bool) array_get($input->toArray(), 'has_'.$additional, false);
            });
        }

        $validator->validate();

        $data = $validator->getData();

        $document = new \App\Documents\SimpleDocument($phpWord);

        $court = \App\Court::first();

        $document->addElement(
            new \App\Documents\Elements\Header(
                \App\Court::first(),
                $plaintiff = \App\Law\Plaintiff::fromArray($data['plaintiff']),
                $respondent = \App\Law\Respondent::fromArray($data['respondent']),
                $claim = new Claim(
                    $data['amount'],
                    custom_date($data['date_of_borrowing']),
                    custom_date($data['date_of_return']),
                    array_get($data, 'interest_bearing_loan.percent'),
                    array_get($data, 'interest_bearing_loan.interval')
                ),
                $tax = $claim->calculateTax($plaintiff, $respondent)
            )
        );

        $document->addTextBreak();

        $document->addElement(
            new \App\Documents\Elements\Title(
                'ИСКОВОЕ ЗАЯВЛЕНИЕ',
                'о взыскании денежных средств.'
            )
        );

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

        $document->addElement(
            new \App\Documents\Elements\SimplePlaintText(
                $claim
            )
        );

        $document->addTextBreak(2);

        $document->addElement($plaintiff->sign());

        return [
            'link' => url($document->save('test.docx')),
            'tax' => $tax->amount(),
            'percents' => $claim->calculate()->toArray()
        ];
    }
}