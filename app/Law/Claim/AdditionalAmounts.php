<?php

namespace App\Law\Claim;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class AdditionalAmounts extends Collection
{
    /**
     * @param Carbon $date
     * @param float $amount
     *
     * @return $this
     */
    public function addReturnedAmount(Carbon $date, float $amount)
    {
        $this->push(new ReturnedAmount($amount, $date));

        return $this;
    }

    /**
     * @param Carbon $date
     * @param float $amount
     *
     * @return $this
     */
    public function addClaimedAmount(Carbon $date, float $amount)
    {
        $this->push(new AdditionalAmount($amount, $date));

        return $this;
    }

    /**
     * Получение списка фактов возвращения денег
     *
     * @return Collection|ReturnedAmount[]
     */
    public function returnedAmounts()
    {
        return $this->filter(function ($amount) {
            return $amount instanceof ReturnedAmount;
        })->values();
    }

    /**
     * @return AdditionalAmount[]|Collection
     */
    public function claimedAmounts()
    {
        return $this->filter(function ($amount) {
            return $amount instanceof AdditionalAmount;
        })->values();
    }

    /**
     * @return static
     */
    public function sortByDate()
    {
        return $this->sortBy(function ($amount) {
            return $amount->date();
        })->values();
    }
}