<?php

namespace Module\FineCalculator;

use App\FederalDistrict;
use App\Law\AdditionalClaimAmount;
use App\Law\Claim;
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

        return new Result(round($totalAmount, 2), round($amount, 2), $this->summary);
    }

    /**
     * @return IntervalsCollection|Interval[]
     */
    public function makeIntervals(): IntervalsCollection
    {
        $from = $this->claim->borrowingDate();
        $returnDate = $this->claim->returnDate();
        $additionalAmounts = $this->claim->additionalAmounts();
        $amount = $this->claim->amount()->amount();

        if ($from->gte($returnDate)) {
            return new IntervalsCollection();
        }

        $intervals = [];

        $break = false;
        while ($rate = $this->rates->find($from)) {
            $to = clone $rate->to();
            $to->addDay(1);

            if ($to->gt($returnDate)) {
                $to = $returnDate;
                $break = true;
            }

            $interval = new Interval($from, $to, $rate->rate(), $amount);

            foreach ($additionalAmounts as $item) {
                if ($interval->contains($item->date())) {

                    if ($item instanceof AdditionalClaimAmount) {

                        $itemFrom = clone $item->date();

                        $intervals[] = new Interval($from, $itemFrom->subDay(1), $rate->rate(), $amount);
                        $amount += $item->amount();

                        $interval = new Interval($item->date(), $to, $rate->rate(), $amount);

                    } else {
                        $itemFrom = clone $item->date();

                        $intervals[] = new Interval($from, $itemFrom->subDay(1), $rate->rate(), $amount);
                        $amount -= $item->amount();

                        $interval = new Interval($item->date(), $to, $rate->rate(), $amount);
                    }

                }
            }

            $intervals[] = $interval;

            if ($break) {
                break;
            }

            $from = $to;
        }

        return new IntervalsCollection($intervals);
    }
}