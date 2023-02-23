<?php

namespace Gogain\LaravelPhoneVerification\Console\Commands;

use Illuminate\Console\Command;

class MigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms-verification:migrate { --force : Force the operation to run when in production }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run sms-verification migrations';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->callSilent('migrate', [
            '--path' => 'vendor/gogain/laravel-phone-verification/database/migrations',
            '--force' => $this->option('force') ?? true,
        ]);

        $this->info('Migration complete.');
    }
}

