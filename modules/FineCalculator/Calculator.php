<?php

namespace Module\FineCalculator;

use App\FederalDistrict;
use App\Law\AdditionalClaimAmount;
use App\Law\Claim;
use App\Law\ReturnedClaimAmount;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Module\ClaimCalculator\Result;
use Module\ClaimCalculator\Summary;

class Calculator
{

    /**
     * @var Claim
     */
    private $claim;

    /**
     * @var Rates
     */
    private $rates;

    /**
     * @var array
     */
    private $summary = [];

    /**
     * Calculator constructor.
     *
     * @param Claim $claim
     * @param FederalDistrict $district
     */
    public function __construct(Claim $claim, FederalDistrict $district)
    {
        $this->claim = $claim;
        $this->rates = new Rates($district);
    }

    /**
     * @return Result
     */
    public function calculate(): Result
    {
        $intervals = $this->makeIntervals();

        $amount = 0;

        foreach ($intervals as $interval) {

            $sum = $interval->calculate();
            $amount += $sum;

            $this->summary[] = new Summary(
                $interval->amount(),
                $interval->rate(),
                $sum,
                $interval->from(),
                $interval->to()
            );
        }

        $totalAmount = $amount + $this->claim->amount()->amount();

        return new Result($totalAmount, $amount, $this->summary);
    }

    /**
     * @return IntervalsCollection|Interval[]
     */
    public function makeIntervals(): IntervalsCollection
    {
        $from = $this->claim->borrowingDate();
        $returnDate = $this->claim->returnDate();
        $amount = $this->claim->amount()->amount();

        if ($from->gte($returnDate)) {
            return new IntervalsCollection();
        }

        /** @var Interval[] $intervals */
        $intervals = [];

        $break = false;

        while ($rate = $this->rates->find($from)) {
            $to = clone $rate->to();

            if ($to->gt($returnDate)) {
                $to = $returnDate;
                $break = true;
            }

            $interval = new Interval($from, $to, $rate->rate(), $amount);

            if ($from->year < 2016) {
                foreach (range($from->year, 2016) as $year) {
                    $endOfYear = Carbon::create($year, 12, 31);
                    $newYear = clone $endOfYear;

                    if ($rate->contains($endOfYear)) {
                        $intervals[] = new Interval($from, $endOfYear, $rate->rate(), $amount);
                        $interval = new Interval($newYear->addDay(1), $to, $rate->rate(), $amount);
                    }
                }
            }

            $toSecond = clone $rate->to();

            $intervals[] = $interval;

            if ($break) {
                break;
            }

            $from = $toSecond->addDay(1);
        }

        $intervals = $this->sortIntervalsByDate($intervals);


        $returnedAmounts = $this->claim->returnedAmounts();

        while ($returnedAmounts->count() > 0) {

            /** @var ReturnedClaimAmount $amount */
            $amount = $returnedAmounts->shift();

            $itemFrom = clone $amount->date();

            foreach ($intervals as $i => $interval) {
                if ($interval->contains($amount->date())) {
                    $intervals[$i] = new Interval($interval->from(), $amount->date(), $interval->rate(), $interval->amount());
                    $intervals[] = new Interval($itemFrom->addDay(1), $interval->to(), $interval->rate(), $interval->amount());

                    $intervals = $this->sortIntervalsByDate($intervals);

                    $i++;
                    while (isset($intervals[$i])) {
                        $intervals[$i]->sub($amount->amount());
                        $i++;
                    }
                }
            }
        }

        $intervals = $this->sortIntervalsByDate($intervals);

        $claimedAmounts = $this->claim->claimedAmounts();

        while ($claimedAmounts->count() > 0) {

            /** @var AdditionalClaimAmount $amount */
            $amount = $claimedAmounts->shift();

            $itemFrom = clone $amount->date();

            foreach ($intervals as $i => $interval) {
                if ($interval->contains($amount->date())) {
                    $intervals[$i] = new Interval($interval->from(), $itemFrom->subDay(1), $interval->rate(), $interval->amount());
                    $intervals[] = new Interval($amount->date(), $interval->to(), $interval->rate(), $interval->amount());

                    $intervals = $this->sortIntervalsByDate($intervals);

                    $i++;
                    while (isset($intervals[$i])) {
                        $intervals[$i]->add($amount->amount());
                        $i++;
                    }
                }
            }
        }

        return (new IntervalsCollection($intervals))->sortBy(function (Interval $interval) {
            return $interval->from();
        })->values();
    }

    /**
     * @param Collection $amounts
     * @param array $intervals
     *
     * @return array
     */
    protected function calculateAdditionalIntervals(Collection $amounts, array $intervals): array
    {
        while ($amounts->count() > 0) {

            /** @var AdditionalClaimAmount $amount */
            $amount = $amounts->shift();

            $itemFrom = clone $amount->date();

            foreach ($intervals as $i => $interval) {
                if ($interval->contains($amount->date())) {
                    if ($interval instanceof AdditionalClaimAmount) {
                        $intervals[$i] = new Interval($interval->from(), $itemFrom->subDay(1), $interval->rate(), $interval->amount());
                        $intervals[] = new Interval($amount->date(), $interval->to(), $interval->rate(), $interval->amount());
                    } else {
                        $intervals[$i] = new Interval($interval->from(), $amount->date(), $interval->rate(), $interval->amount());
                        $intervals[] = new Interval($itemFrom->addDay(1), $interval->to(), $interval->rate(), $interval->amount());
                    }

                    $intervals = $this->sortIntervalsByDate($intervals);

                    $i++;
                    while (isset($intervals[$i])) {

                        if ($interval instanceof AdditionalClaimAmount) {
                            $intervals[$i]->add($amount->amount());
                        } else {
                            $intervals[$i]->sub($amount->amount());
                        }

                        $i++;
                    }
                }
            }
        }

        return $intervals;
    }

    /**
     * @param $intervals
     *
     * @return array
     */
    protected function sortIntervalsByDate($intervals): array
    {
        return array_values(array_sort($intervals, function (Interval $interval) {
            return $interval->from();
        }));
    }


}