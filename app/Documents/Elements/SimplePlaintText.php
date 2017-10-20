<?php

namespace App\Documents\Elements;

use App\Contracts\Documents\ElementInterface;
use App\Law\ClaimAmount;
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
     * @param Carbon $borrowingDate
     * @param Carbon $returnDate
     * @param ClaimAmount $amount
     */
    public function __construct(Carbon $borrowingDate, Carbon $returnDate, ClaimAmount $amount)
    {
        $this->amount = $amount;
        $this->borrowingDate = $borrowingDate;
        $this->returnDate = $returnDate;
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
            $this->borrowingDate->format('d.m.Y г.')
        ));

        $container->addText(sprintf(
            "\tСрок возврата денежных средств был определен сторонами %s",
            $this->returnDate->format('d.m.Y г.')
        ));

        $container->addText("\tСогласно ч. 1 ст. 810 ГК РФ, заемщик обязан возвратить займодавцу полученную сумму займа в срок и в порядке, которые предусмотрены договором займа.");

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
            "заверенная копия расписки от 01.01.2016 г. – 2 экз. (для суда и для Ответчика)."
        ]))->insertTo($container);
    }
}