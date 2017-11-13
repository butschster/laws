<?php

namespace App\Law;

use App\FederalDistrict;
use App\Law\Claim\AdditionalAmount;
use App\Law\Claim\AdditionalAmounts;
use App\Law\Claim\ClaimAmount;
use App\Law\Claim\ClaimTax;
use App\Law\Claim\Plaintiff;
use App\Law\Claim\Respondent;
use App\Law\Claim\ReturnedAmount;
use Carbon\Carbon;

/**
 * Займ
 *
 * @package App\Law
 */
class Claim
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
     * @var AdditionalAmounts|AdditionalAmount[]|ReturnedAmount
     */
    private $additionalAmounts = [];

    /**
     * Процентная ставка за пользование
     *
     * @var InterestRate
     */
    private $interestRate;

    /**
     * Неустойка за несвоевременный возврат суммы займа
     *
     * @var InterestRate
     */
    private $forfeit;

    /**
     * @var Plaintiff
     */
    private $plaintiff;

    /**
     * @var Respondent
     */
    private $respondent;

    /**
     * @param float $amount Сумма займа
     * @param Carbon $borrowingDate Дата выдачи
     * @param Carbon $returnDate Дата возврата
     * @param float $percents Процент
     * @param string $interval Период начисления
     */
    public function __construct(
        float $amount = 0,
        Carbon $borrowingDate,
        Carbon $returnDate,
        float $percents = 0,
        string $interval = InterestRate::MONTHLY
    ) {
        $this->additionalAmounts = new AdditionalAmounts();
        $this->amount = new ClaimAmount($amount);

        $this->borrowingDate = $borrowingDate;
        $this->returnDate = $returnDate;

        $this->interestRate = new InterestRate($percents, $interval);
    }

    /**
     * @param Plaintiff $plaintiff
     * @param Respondent $respondent
     */
    public function setParticipants(Plaintiff $plaintiff, Respondent $respondent)
    {
        $this->plaintiff = $plaintiff;
        $this->respondent = $respondent;
    }

    /**
     * @return Plaintiff
     */
    public function plaintiff(): Plaintiff
    {
        return $this->plaintiff;
    }

    /**
     * @return Respondent
     */
    public function respondent(): Respondent
    {
        return $this->respondent;
    }

    /**
     * Получение суммы займа
     *
     * @return ClaimAmount
     */
    public function amount(): ClaimAmount
    {
        return $this->amount;
    }

    /**
     * Добавление факта возврата денег
     *
     * @param Carbon $date Дата возврата
     * @param float $amount Сумма
     *
     * @return $this
     */
    public function addReturnedMoney(Carbon $date, float $amount)
    {
        $this->additionalAmounts->addReturnedAmount($date, $amount);

        return $this;
    }

    /**
     * Получение списка фактов возвращения денег
     *
     * @return AdditionalAmounts|ReturnedAmount[]
     */
    public function returnedAmounts(): AdditionalAmounts
    {
        return $this->additionalAmounts()->returnedAmounts();
    }

    /**
     * Добавление факта дополнительного займа денег
     *
     * @param Carbon $date Дата взятия
     * @param float $amount Сумма
     *
     * @return $this
     */
    public function addClaimedMoney(Carbon $date, float $amount)
    {
        $this->additionalAmounts->addClaimedAmount($date, $amount);

        return $this;
    }

    /**
     * @return AdditionalAmount[]|AdditionalAmounts
     */
    public function claimedAmounts(): AdditionalAmounts
    {
        return $this->additionalAmounts()->claimedAmounts();
    }

    /**
     * @return AdditionalAmount[]|ReturnedAmount|AdditionalAmounts
     */
    public function additionalAmounts(): AdditionalAmounts
    {
        return $this->additionalAmounts->sortByDate();
    }

    /**
     * @return InterestRate
     */
    public function interestRate(): InterestRate
    {
        return $this->interestRate;
    }

    /**
     * @return bool
     */
    public function hasForfeit(): bool
    {
        return $this->forfeit instanceof InterestRate;
    }

    /**
     * Установка неустойки за несвоевременный возврат суммы займа
     *
     * @param float $percents Процентная ставка
     * @param string $interval Период начисления
     * @return void
     */
    public function setForfeit(float $percents, string $interval = InterestRate::MONTHLY)
    {
        $this->forfeit = new InterestRate($percents, $interval);
    }

    /**
     * Получение даты выдачи займа
     *
     * @return Carbon
     */
    public function borrowingDate(): Carbon
    {
        return $this->borrowingDate;
    }

    /**
     * Получение даты возврата
     *
     * @return Carbon
     */
    public function returnDate(): Carbon
    {
        return $this->returnDate;
    }

    public function court(): \App\Court
    {
        Court::detect($this->plaintiff, $this->respondent);
    }

    /**
     * Получение расчитанной суммы процентов по займу
     *
     * @return \Module\ClaimCalculator\Contracts\Result
     */
    public function calculate(): \Module\ClaimCalculator\Contracts\Result
    {
        $calculator = new \Module\ClaimCalculator\Calculator(
            $this->amount->amount(),
            $this->interestRate,
            $this->borrowingDate(),
            $this->returnDate,
            $this->additionalAmounts()
        );

        return $calculator->calculate();
    }

    /**
     * Получение расчитанной суммы пени по займу
     *
     * @return \Module\ClaimCalculator\Contracts\Result
     */
    public function calculatePennies(): \Module\ClaimCalculator\Contracts\Result
    {
        $result = $this->calculate();

        $calculator = new \Module\ClaimCalculator\Calculator(
            $result->amount(),
            $this->forfeit,
            $this->returnDate(),
            now()
        );

        return $calculator->calculate();
    }

    /**
     * Получение расчитаной суммы процентов по ст.395
     *
     * @param FederalDistrict $district
     *
     * @return \Module\FineCalculator\Contracts\Result
     */
    public function calculate395(FederalDistrict $district): \Module\FineCalculator\Contracts\Result
    {
        $result = $this->calculate();

        $calculator = new \Module\FineCalculator\Calculator(
            $result->amount(),
            $this->returnDate(),
            now(),
            $district,
            $this->additionalAmounts()
        );

        return $calculator->calculate();
    }

    /**
     * @return ClaimTax
     */
    public function calculateTax(): ClaimTax
    {
        return new ClaimTax(
            $this->calculate()->amountWithPercents(),
            $this->plaintiff,
            $this->respondent
        );
    }
}