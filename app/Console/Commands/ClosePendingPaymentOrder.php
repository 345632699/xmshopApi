<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Order;
use Carbon\Carbon;
use Mockery\Exception;

class ClosePendingPaymentOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:close';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '关闭过期的待付款订单';

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
            $expire_date = Carbon::now()->modify('-1 hours');
            $expire_orders = Order::whereRaw('order_date <= ? AND order_status = ?',[$expire_date,0]);
            $expire_orders -> update(['order_status'=>9]);
        }catch (Exception $e){
            return $e->getMessage();
        }
    }
}
