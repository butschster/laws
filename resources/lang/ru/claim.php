<?php

return [
    'fields' => [
        'amount' => 'Сумма займа',
        'date_of_borrowing' => 'Дата выдачи',
        'date_of_return' => 'Дата возврата',
        'is_interest_bearing_loan' => 'Займ является процентным',
        'interest_bearing_loan' => [
            'interval' => 'Период',
            'percent' => 'Процентная ставка'
        ],
        'has_returned_money' => 'Осуществлялся ли должником частичный возврат суммы займа?',
        'partly_returned_money.*.date' => 'Дата возврата',
        'partly_returned_money.*.amount' => 'Сумма',
        'has_claimed_money' => 'Осуществлялось ли должником учеличение займа?',
        'claimed_money.*.date' => 'Дата займа',
        'claimed_money.*.amount' => 'Сумма',
    ]
];