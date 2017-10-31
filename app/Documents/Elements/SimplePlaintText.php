<?php

namespace App\Documents\Elements;

use App\Contracts\Documents\ElementInterface;
use App\Law\Amount;
use App\Law\Claim;
use PhpOffice\PhpWord\Element\AbstractContainer;

class SimplePlaintText implements ElementInterface
{

    /**
     * @var \App\Law\ClaimAmount
     */
    private $amount;

    /**
     * @var Claim
     */
    private $claim;

    /**
     * @param Claim $claim
     */
    public function __construct(Claim $claim)
    {
        $this->claim = $claim;
        $this->amount = $claim->amount();
    }

    /**
     * @param AbstractContainer $container
     *
     * @return void
     */
    public function insertTo(AbstractContainer $container)
    {
        $container->addText(sprintf(
            "Между Истцом и Ответчиком был заключен договор беспроцентного займа на сумму %s, что подтверждается распиской от %s",
            (string) $this->amount,
            format_date($this->claim->borrowingDate())
        ));

        $container->addText(sprintf(
            "Срок возврата денежных средств был определен сторонами %s",
            format_date($this->claim->returnDate())
        ));

        $container->addText("Согласно ч. 1 ст. 810 ГК РФ, заемщик обязан возвратить займодавцу полученную сумму займа в срок и в порядке, которые предусмотрены договором займа.");

        $amount = $this->claim->calculate();
        $returned = $this->claim->returnedAmounts();
        if ($returned->count() > 0) {
            $container->addText("Из суммы займа Ответчик возвратил:");

            $amounts = [];
            foreach ($this->claim->returnedAmounts() as $amount) {
                $amounts[] = sprintf('%s - %s', format_date($amount->date()), (string) $amount);
            }

            (new BulletList($amounts))->insertTo($container);

            $container->addTextBreak();

            $container->addText(sprintf(
                "Таким образом, Ответчик на день предъявления искового заявления имеет задолженность по основному обязательству в размере %s",
                (string) new Amount($amount->percents()) /*$this->claim->residualAmount()*/
            ));
        }

        $container->addText("В соответствие с ч.1 ст.310 ГК РФ, односторонний отказ от исполнения обязательства и одностороннее изменение его условий не допускаются.");

        $container->addText("На основании изложенного, руководствуясь ст. 131-132 ГПК РФ, прошу:");

        (new NumberedList([
            sprintf(
                'Взыскать с Ответчика сумму задолженности по договору займа в размере %s',
                (string) new Amount($amount->amountWithPercents())
            ),
            "Судебные расходы возложить на Ответчика."
        ]))->insertTo($container);

        $container->addTextBreak();

        $container->addText("Приложения:");

        (new BulletList([
            "экземпляр настоящего искового заявления для Ответчика;",
            "заверенная копия страниц паспорта Истца (титульная и регистрация);",
            "квитанция об оплате государственной пошлины;",
            sprintf("заверенная копия расписки от %s – 2 экз. (для суда и для Ответчика).", format_date($this->claim->borrowingDate()))
        ]))->insertTo($container);
    }
}