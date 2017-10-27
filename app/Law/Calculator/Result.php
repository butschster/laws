<?php

namespace App\Law\Calculator;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;

class Result implements Arrayable
{

    /**
     * @var float
     */
    private $amount;

    /**
     * @var float
     */
    private $percents;

    /**
     * @var array|Summary[]
     */
    private $summary = [];

    /**
     * @param float $amount
     * @param float $percents
     * @param array $summary
     */
    public function __construct(float $amount, float $percents, array $summary)
    {
        $this->amount = $amount;
        $this->percents = $percents;
        $this->summary = collect($summary);
    }

    /**
     * @return float
     */
    public function amount(): float
    {
        return $this->amount;
    }

    /**
     * @return float
     */
    public function amountWithPercents(): float
    {
        return $this->amount + $this->percents;
    }

    /**
     * @return float
     */
    public function percents(): float
    {
        return $this->percents;
    }

    /**
     * @return Collection
     */
    public function summary(): Collection
    {
        return $this->summary;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'amount' => $this->amount,
            'percents' => $this->percents,
            'amount_with_percents' => $this->amountWithPercents(),
            'summary' => $this->summary,
        ];
    }
}