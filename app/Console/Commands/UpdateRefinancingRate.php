<?php

namespace App\Console\Commands;

use App\Services\CBR\RefinancingRate;
use Illuminate\Console\Command;
use Psr\Log\LoggerInterface;

class UpdateRefinancingRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:refinancing-rate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Получение ставки рефинансирования с сайта ЦБ.';

    /**
     * @var RefinancingRate
     */
    private $rate;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Create a new command instance.
     *
     * @param RefinancingRate $rate
     * @param LoggerInterface $logger
     */
    public function __construct(RefinancingRate $rate, LoggerInterface $logger)
    {
        parent::__construct();
        $this->rate = $rate;
        $this->logger = $logger;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Getting refinancing rate');

        try {
            $rate = $this->rate->get();
            $this->info('Current rate: ['.$rate.']');
        } catch (\SoapFault $exception) {
            $this->error('Getting refinancing rate error. Reason: '. $exception->getMessage());
        }
    }
}
