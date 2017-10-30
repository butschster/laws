<?php

namespace App\Law;

use App\FederalDistrict;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Module\ClaimCalculator\Contracts\Result;

/**
 * Займ
 *
 * @package App\Law
 */
class Claim
{
    const MONTHLY = 'monthly';
    const DAILY = 'daily';
    const YEARLY = 'yearly';
    const WEEKLY = 'weekly';

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
     * @var int
     */
    private $percents;

    /**
     * @var string
     */
    private $interval;

    /**
     * @var Collection|AdditionalClaimAmount[]|ReturnedClaimAmount
     */
    private $additionalAmounts = [];

    /**
     * @param float $amount Сумма займа
     * @param Carbon $borrowingDate Дата выдачи
     * @param Carbon $returnDate Дата возврата
     * @param int $percents Процент
     * @param string $interval Период начисления
     */
    public function __construct(float $amount = 0, Carbon $borrowingDate, Carbon $returnDate, int $percents = 0, string $interval = self::MONTHLY)
    {
        $this->amount = new ClaimAmount($amount);

        $this->borrowingDate = $borrowingDate;
        $this->returnDate = $returnDate;
        $this->percents = $percents;
        $this->interval = $interval;

        $this->additionalAmounts = new Collection();
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
        $this->additionalAmounts->push(new ReturnedClaimAmount($amount, $date));

        return $this;
    }

    /**
     * Получение списка фактов возвращения денег
     *
     * @return Collection|ReturnedClaimAmount[]
     */
    public function returnedAmounts(): Collection
    {
        return $this->additionalAmounts()->filter(function ($amount) {
            return $amount instanceof ReturnedClaimAmount;
        });
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
        $this->additionalAmounts->push(new AdditionalClaimAmount($amount, $date));

        return $this;
    }

    /**
     * @return AdditionalClaimAmount[]|Collection
     */
    public function claimedAmounts(): Collection
    {
        return $this->additionalAmounts()->filter(function ($amount) {
            return $amount instanceof AdditionalClaimAmount;
        });
    }

    /**
     * @return AdditionalClaimAmount[]|ReturnedClaimAmount|Collection
     */
    public function additionalAmounts(): Collection
    {
        return $this->additionalAmounts->sortBy(function ($amount) {
            return $amount->date();
        })->values();
    }

    /**
     * Проверка, является ли займ процентным
     *
     * @return bool
     */
    public function hasPercents(): bool
    {
        return $this->percents() > 0;
    }

    /**
     * Получение процентной ставки
     *
     * @return int
     */
    public function percents(): int
    {
        return $this->percents;
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
     * Получение периода начисления
     *
     * @return string
     */
    public function interval(): string
    {
        return $this->interval;
    }

    /**
     * Получение расчитанной суммы процентов по займу
     *
     * @return Result
     */
    public function calculate(): Result
    {
        return (new \Module\ClaimCalculator\Calculator($this))->calculate();
    }

    /**
     * Получение расчитаной суммы процентов по ст.395
     *
     * @param FederalDistrict $district
     *
     * @return Result
     */
    public function calculate395(FederalDistrict $district): Result
    {
        return (new \Module\FineCalculator\Calculator($this, $district))->calculate();
    }
}