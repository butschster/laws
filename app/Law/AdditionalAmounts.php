<?php

namespace App\Law;

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
        $this->push(new ReturnedClaimAmount($amount, $date));

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
        $this->push(new AdditionalClaimAmount($amount, $date));

        return $this;
    }

    /**
     * Получение списка фактов возвращения денег
     *
     * @return Collection|ReturnedClaimAmount[]
     */
    public function returnedAmounts()
    {
        return $this->filter(function ($amount) {
            return $amount instanceof ReturnedClaimAmount;
        })->values();
    }

    /**
     * @return AdditionalClaimAmount[]|Collection
     */
    public function claimedAmounts()
    {
        return $this->filter(function ($amount) {
            return $amount instanceof AdditionalClaimAmount;
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