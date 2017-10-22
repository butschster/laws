<?php

namespace App\Documents\Elements;

use App\Contracts\Documents\ElementInterface;
use App\Law\ClaimAmount;
use App\Law\ReturnedClaimAmount;
use Carbon\Carbon;
use PhpOffice\PhpWord\Element\AbstractContainer;

class SimplePlaintText implements ElementInterface
{

    /**
     * @var ClaimAmount
     */
    private $amount;

    /**
     * @var Carbon
     */
    private $borrowingDate;

    /**
     * @var Carbon
     */
    private $returnDate;

    /**
     * @var ReturnedClaimAmount
     */
    private $returnedAmount;

    /**
     * @param Carbon $borrowingDate
     * @param Carbon $returnDate
     * @param ClaimAmount $amount
     * @param ReturnedClaimAmount $returnedAmount
     */
    public function __construct(Carbon $borrowingDate, Carbon $returnDate, ClaimAmount $amount, ReturnedClaimAmount $returnedAmount)
    {
        $this->amount = $amount;
        $this->borrowingDate = $borrowingDate;
        $this->returnDate = $returnDate;
        $this->returnedAmount = $returnedAmount;
    }

    /**
     * @param AbstractContainer $container
     *
     * @return void
     */
    public function insertTo(AbstractContainer $container)
    {
        $container->addText(sprintf(
            "\tМежду Истцом и Ответчиком был заключен договор беспроцентного займа на сумму %s, что подтверждается распиской от %s",
            (string) $this->amount,
            format_date($this->borrowingDate)
        ));

        $container->addText(sprintf(
            "\tСрок возврата денежных средств был определен сторонами %s",
            format_date($this->returnDate)
        ));

        $container->addText("\tСогласно ч. 1 ст. 810 ГК РФ, заемщик обязан возвратить займодавцу полученную сумму займа в срок и в порядке, которые предусмотрены договором займа.");

        if ($this->returnedAmount->amount() > 0) {
            $container->addText(sprintf(
                "\tИз суммы займа Ответчик возвратил %s только %s. Таким образом, Ответчик на день предъявления искового заявления имеет задолженность по основному обязательству в размере %s",
                $this->returnedAmount->hasReturnDate() ? format_date($this->returnedAmount->returnDate()) : '',
                (string) $this->returnedAmount,
                (string) $this->amount->sub($this->returnedAmount)
            ));
        }

        $container->addText("\tВ соответствие с ч.1 ст.310 ГК РФ, односторонний отказ от исполнения обязательства и одностороннее изменение его условий не допускаются.");

        $container->addText("На основании изложенного, руководствуясь ст. 131-132 ГПК РФ, прошу:");

        (new NumberedList([
            sprintf(
                'Взыскать с Ответчика сумму задолженности по договору займа в размере %s',
                (string) $this->amount
            ),
            "Судебные расходы возложить на Ответчика."
        ]))->insertTo($container);

        $container->addTextBreak();

        $container->addText("Приложения:");

        (new BulletList([
            "экземпляр настоящего искового заявления для Ответчика;",
            "заверенная копия страниц паспорта Истца (титульная и регистрация);",
            "квитанция об оплате государственной пошлины;",
            sprintf("заверенная копия расписки от %s – 2 экз. (для суда и для Ответчика).", format_date($this->borrowingDate))
        ]))->insertTo($container);
    }
}