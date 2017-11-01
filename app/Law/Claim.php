<?php

namespace App\Law;

use App\FederalDistrict;
use Carbon\Carbon;
use Illuminate\Support\Collection;

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
     * @var AdditionalAmounts|AdditionalClaimAmount[]|ReturnedClaimAmount
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
     * @return AdditionalAmounts|ReturnedClaimAmount[]
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
     * @return AdditionalClaimAmount[]|AdditionalAmounts
     */
    public function claimedAmounts(): AdditionalAmounts
    {
        return $this->additionalAmounts()->claimedAmounts();
    }

    /**
     * @return AdditionalClaimAmount[]|ReturnedClaimAmount|AdditionalAmounts
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
     * @param Plaintiff $plaintiff
     * @param Respondent $respondent
     *
     * @return Tax
     */
    public function calculateTax(Plaintiff $plaintiff, Respondent $respondent): Tax
    {
        return new ClaimTax(
            $this->calculate()->amountWithPercents(),
            $plaintiff,
            $respondent
        );
    }
}