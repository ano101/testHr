<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetupMeilisearchIndexes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meilisearch:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Configure Meilisearch indexes with proper settings';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Configuring Meilisearch indexes...');

        // Sync index settings from config
        $this->call('scout:sync-index-settings');

        $this->newLine();

        // Import products
        $this->info('Importing products...');
        $this->call('scout:import', ['model' => 'App\Models\Product']);

        $this->newLine();
        $this->info('Meilisearch setup completed successfully!');

        return self::SUCCESS;
    }
}
