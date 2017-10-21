<?php

namespace App\Console\Commands;

use App\Services\Fias\Indexer;
use Illuminate\Console\Command;

class IndexFiasDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fias:index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var Indexer
     */
    private $indexer;

    /**
     * @param Indexer $indexer
     */
    public function __construct(Indexer $indexer)
    {
        parent::__construct();

        $this->indexer = $indexer;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach (range(1, 7) as $level) {
            $this->info("Indexing level [{$level}].");

            $this->indexer->index($level);

            $this->info("Level [{$level}] indexed.");
        }
    }
}
