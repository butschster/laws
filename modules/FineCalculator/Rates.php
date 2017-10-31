<?php

namespace Module\FineCalculator;

use App\FederalDistrict;
use App\RefinancingRate;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Module\FineCalculator\Exceptions\DistrictRatesNotFound;

class Rates implements Arrayable
{

    /**
     * @var Collection|Rate[]
     */
    private $rates;

    /**
     * @param FederalDistrict $district
     *
     * @throws DistrictRatesNotFound
     */
    public function __construct(FederalDistrict $district)
    {
        if (! $district->getKey()) {
            throw new DistrictRatesNotFound();
        }

        $dates = collect(config('court_rates.dates', []))->map(function ($date) {
            return Carbon::parse($date);
        });

        $rates = config('court_rates.rates.'.$district->id);

        $this->rates = collect($rates)->map(function ($rate, $i) use ($dates) {
            return [
                'rate' => $rate,
                'date' => $dates->get($i),
            ];
        });

        $this->rates->push([
            'date' => Carbon::parse('2012-09-14'),
            'rate' => 8.25,
        ]);

        foreach (RefinancingRate::all() as $rate) {
            $this->rates->push([
                'date' => $rate->created_at,
                'rate' => $rate->rate,
            ]);
        }

        $this->rates = $this->rates->sortBy('date')->values();

        $this->rates = $this->rates->map(function ($rate, $i) {

            $next = $this->rates->get($i + 1);

            $to = $next ? clone $next['date'] : Carbon::now()->addYears(50);

            return new Rate($rate['rate'], $rate['date'], $to->subDay(1));
        });
    }

    /**
     * Получение списка ключевых ставок
     *
     * Collection|Rate[]
     */
    public function rates(): Collection
    {
        return $this->rates;
    }

    /**
     * Поиск ключевой ставки по дате
     *
     * @param Carbon $date
     *
     * @return Rate|null
     */
    public function find(Carbon $date)
    {
        foreach ($this->rates as $rate) {
            if ($rate->contains($date)) {
                return $rate;
            }
        }
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->rates->toArray();
    }
}