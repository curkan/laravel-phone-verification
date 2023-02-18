<?php

namespace Gogain\LaravelPhoneVerification\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class ExampleCommand extends Command
{
    protected $signature = 'example-command';

    protected $description = 'Example Command';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info("Command executed with config param value " . Config::get('example.param'));

        return 0;
    }
}
