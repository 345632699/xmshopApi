<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mockery\Exception;

class UpdateDelivery extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deliveries:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '已发货订单10天内没有确认收货系统自动收货';

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
        try{
            $expire_orders = \DB::select(
                'update xm_order_headers oh,xm_delivery de 
               set oh.order_status = 4,
                   oh.completion_date = now(),
                   de.delivery_status = 2
               where oh.request_close_date <= now()
               and oh.uid = de.order_header_id');
        }catch (Exception $e){
            return $e->getMessage();
        }
    }
}

