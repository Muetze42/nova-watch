<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ClearComparisonsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clear-comparisons';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush the Nova comparisons cache';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        (new Filesystem())->cleanDirectory(storage_path('nova/comparisons'));

        $this->info('Nova comparisons cache cleared successfully.');
    }
}
