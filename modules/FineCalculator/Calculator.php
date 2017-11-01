<?php

namespace Module\FineCalculator;

use App\FederalDistrict;
use App\Law\AdditionalAmounts;
use App\Law\ReturnedClaimAmount;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Module\FineCalculator\Contracts\Calculator as CalculatorContract;
use Module\FineCalculator\Contracts\Result as ResultContract;

class Calculator implements CalculatorContract
{
    /**
     * @var Rates
     */
    private $rates;

    /**
     * @var float
     */
    private $amount;

    /**
     * @var AdditionalAmounts
     */
    private $amounts;

    /**
     * @var FederalDistrict
     */
    private $district;

    /**
     * @var Carbon
     */
    private $from;

    /**
     * @var Carbon
     */
    private $to;

    /**
     * @param float $amount
     * @param Carbon $from
     * @param Carbon $to
     * @param FederalDistrict $district
     * @param AdditionalAmounts|null $amounts
     */
    public function __construct(float $amount, Carbon $from, Carbon $to, FederalDistrict $district, AdditionalAmounts $amounts = null)
    {
        $this->rates = new Rates($district);
        $this->amount = $amount;
        $this->amounts = $amounts;
        $this->district = $district;
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return ResultContract
     */
    public function calculate(): ResultContract
    {
        return new Result($this->amount, $this->makeIntervals());
    }

    /**
     * @return IntervalsCollection|Interval[]
     */
    protected function makeIntervals(): IntervalsCollection
    {
        $from = $this->from;
        $returnDate = $this->to;
        $amount = $this->amount;

        /** @var Interval[] $intervals */
        $intervals = new IntervalsCollection();

        if ($from->gte($returnDate)) {
            return $intervals;
        }

        $break = false;

        while ($rate = $this->rates->find($from)) {
            $to = clone $rate->to();

            if ($to->gt($returnDate)) {
                $to = $returnDate;
                $break = true;
            }

            $interval = new Interval($from, $to, $rate->rate(), $amount);

            if ($from->year < $to->year) {
                foreach (range($from->year, $to->year) as $year) {
                    $endOfYear = Carbon::create($year, 12, 31);
                    $newYear = clone $endOfYear;

                    if ($rate->contains($endOfYear)) {
                        $intervals->push(new Interval($from, $endOfYear, $rate->rate(), $amount));
                        $interval = new Interval($newYear->addDay(1), $to, $rate->rate(), $amount);
                    }
                }
            }


            $toSecond = clone $rate->to();
            $intervals->push($interval);

            if ($break) {
                break;
            }

            $from = $toSecond->addDay(1);
        }

        if ($this->amounts) {
            $intervals = $this->calculateAdditionAmounts($intervals, $this->amounts->returnedAmounts());
            $intervals = $this->calculateAdditionAmounts($intervals, $this->amounts->claimedAmounts());
        }

        return $intervals->sortByDate();
    }

    /**
     * @param IntervalsCollection $intervals
     * @param Collection $additionalAmounts
     *
     * @return IntervalsCollection
     */
    protected function calculateAdditionAmounts(IntervalsCollection $intervals, Collection $additionalAmounts): IntervalsCollection
    {
        $intervals = $intervals->sortByDate();

        while ($additionalAmounts->count() > 0) {

            /** @var ReturnedClaimAmount $amount */
            $amount = $additionalAmounts->shift();

            $itemFrom = clone $amount->date();

            foreach ($intervals as $i => $interval) {
                if ($interval->contains($amount->date())) {

                    if ($amount instanceof ReturnedClaimAmount) {
                        $intervals[$i] = new Interval($interval->from(), $amount->date(), $interval->rate(), $interval->amount());
                        $intervals[] = new Interval($itemFrom->addDay(1), $interval->to(), $interval->rate(), $interval->amount());
                    } else {
                        $intervals[$i] = new Interval($interval->from(), $itemFrom->subDay(1), $interval->rate(), $interval->amount());
                        $intervals[] = new Interval($amount->date(), $interval->to(), $interval->rate(), $interval->amount());
                    }

                    $intervals = $intervals->sortByDate();

                    $i++;
                    while (isset($intervals[$i])) {
                        $intervals[$i]->consider($amount);

                        $i++;
                    }
                }
            }
        }

        return $intervals;
    }

}