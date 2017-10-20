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

Route::get('/', function () {
    return view('welcome');
});

Route::get('document', function (\PhpOffice\PhpWord\PhpWord $phpWord) {

    $document = new \App\Documents\SimpleDocument($phpWord);

    $document->addElement(
        new \App\Documents\Elements\Header(
            \App\Court::first(),
            $plaintiff = new \App\Law\Plaintiff(
                'Вася Пупкин',
                '112233, г. Москва, ул. Ленинский проспект, д.12, кв.3',
                '+7 926 123-45-67'
            ),
            new \App\Law\Respondent(
                'Дуня Кулачкова',
                '445566, Московская область, г. Химки, ул. Чкалова, д. 6, кв. 666',
                '+7 926 123-45-67'
            ),
            $claim = new \App\Law\ClaimAmount(10000.3413123123),
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

    $document->addElement(
        new \App\Documents\Elements\SimplePlaintText(
            now(),
            now()->addYear(),
            $claim
        )
    );

    $document->addTextBreak(2);


    $document->addElement((new \App\Documents\Elements\UserSign($plaintiff)));

    return response()->download(
        $document->save('test.docx')
    );
});