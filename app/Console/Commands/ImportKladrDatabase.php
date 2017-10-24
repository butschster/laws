<?php

namespace App\Console\Commands;

use App\Services\Kladr\Database\Importer;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Finder\SplFileInfo;

class ImportKladrDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kladr:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var Importer
     */
    private $importer;

    /**
     * ImportKladrDatabase constructor.
     *
     * @param Filesystem $filesystem
     * @param Importer $importer
     */
    public function __construct(Filesystem $filesystem, Importer $importer)
    {
        parent::__construct();
        $this->filesystem = $filesystem;
        $this->importer = $importer;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /** @var SplFileInfo[] $files */
        $files = $this->filesystem->files(storage_path('app/fias'));

        foreach ($files as $file) {
            $this->info("Import database from file: {$file->getBasename()}");

            $this->importer->import($file);
        }
    }
}
