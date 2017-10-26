<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Validation\Rule;

Route::get('/', function () {
    return view('welcome');
});

Route::post('claim-calculator', 'ClaimCalculatorController@calculate');

Route::post('store-document', function (\Illuminate\Http\Request $request, \PhpOffice\PhpWord\PhpWord $phpWord) {

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
        'is_interest_bearing_loan' => 'boolean',
        'interest_bearing_loan.interval' => ['required_if:is_interest_bearing_loan,true', Rule::in(['daily', 'weekly', 'monthly', 'yearly'])],
        'interest_bearing_loan.percent' => 'required_if:is_interest_bearing_loan,true|numeric|min:0|max:100',

        'plaintiff.type' => ['required', Rule::in(['citizen', 'ip', 'organization'])],
        'plaintiff.name' => 'required|fio',
        'plaintiff.address' => 'required|address',
        'plaintiff.has_fact_address' => 'boolean',
        'plaintiff.phone' => 'phone',

        'respondent.type' => ['required', Rule::in(['citizen', 'ip', 'organization'])],
        'respondent.name' => 'required|fio',
        'respondent.address' => 'required|address',
        'respondent.has_fact_address' => 'boolean',
        'respondent.phone' => 'phone',

        'partly_returned_money.*.date' => 'required|date_format:d.m.Y|after:date_of_borrowing|before:date_of_return',
        'partly_returned_money.*.amount' => 'required|numeric|min:0',
    ]);

    $validator->sometimes('respondent.fact_address', 'required|address', function ($input) {
        return (bool) array_get($input->toArray(), 'respondent.has_fact_address', false);
    });

    $validator->sometimes('plaintiff.fact_address', 'required|address', function ($input) {
        return (bool) array_get($input->toArray(), 'plaintiff.has_fact_address', false);
    });

    $validator->validate();

    $data = $validator->getData();

    $document = new \App\Documents\SimpleDocument($phpWord);

    $document->addElement(
        new \App\Documents\Elements\Header(
            \App\Court::first(),
            $plaintiff = \App\Law\Plaintiff::fromArray($data['plaintiff']),
            \App\Law\Respondent::fromArray($data['respondent']),
            $claim = new \App\Law\Claim(
                $data['amount'],
                custom_date($data['date_of_borrowing']),
                custom_date($data['date_of_return']),
                array_get($data, 'interest_bearing_loan.percent'),
                array_get($data, 'interest_bearing_loan.interval')
            ),
            new \App\Law\Tax(300)
        )
    );

    $document->addTextBreak();

    $document->addElement(
        new \App\Documents\Elements\Title(
            'ИСКОВОЕ ЗАЯВЛЕНИЕ',
            'о взыскании денежных средств.'
        )
    );

    foreach (array_get($data, 'partly_returned_money', []) as $row) {
        $claim->addReturnedMoney(custom_date($row['date']), $row['amount']);
    }

    $document->addElement(
        new \App\Documents\Elements\SimplePlaintText(
            $claim
        )
    );

    $document->addTextBreak(2);

    $document->addElement(new \App\Documents\Elements\UserSign($plaintiff));

    return response()->json([
        'path' => $document->save('test.docx')
    ]);
});

Route::get('kladr', function (\App\Services\Kladr\Client $client, \App\Services\Fias\Service $service) {

    $kladr =  $client->findByAddress('ст. Преображенская, ул. Ленина');

    dd($kladr->toArray(), $service->searchByCode($kladr->first()->id()));

});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
