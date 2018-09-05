<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateClient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clients:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update the frozen money';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //更新逻辑
        file_put_contents('~/l.PHPog.txt',date('Y-m-d H:i:s').PHP_EOL,FILE_APPEND);
    }
}
