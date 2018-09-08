<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Model\Order;
use App\Model\ClientAmount;
use App\Model\Client;
use Carbon\Carbon;
use Mockery\Exception;

class UpdateFreezingAmount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'freezingAmount:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '订单完成五天后，系统解冻相应冻结金';

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
            $deadline = Carbon::now()->modify('-5 hours');//过期时间

            //筛选到期的订单
            $expire_orders = Order::select('uid','client_id','completion_date')
                ->whereRaw('completion_date <= ? AND unfreezing_amount_flag = ?',[$deadline,'N'])
                ->get();

            foreach ($expire_orders as $order){
                //查找订单的推广人
                $find_spreaders = \DB::table('client_link_treepaths')
                    ->select('path_begin_client_id','dist','path_end_client_id')
                    ->whereRaw('dist != ? and path_end_client_id = ?',[0,$order->client_id])
                    ->get();

                foreach ($find_spreaders as $spreader){
                    $date = Carbon::now();//当前时间
                    $spread_amount = $spreader->dist == 1 ?  \SystemConfig::$first_spread_amount : \SystemConfig::$second_spread_amount;

                    //在资金流水表中插入资金解冻记录
                    $nick_name = Client::where('id',$spreader->path_begin_client_id)->first()->nick_name;//获取微信名
                    $memo = $nick_name.'增加可提现金额'.$spread_amount.'元';//拼接memo字段

                    $insert_amount_flow = \DB::table('client_amount_flow')
                        ->insert(['client_id' => $spreader->path_begin_client_id,
                            'child_id' => $spreader->path_end_client_id,
                            'amount' => $spread_amount,
                            'memo' => $memo,
                            'spread_flag' => $spreader->dist,
                            'status' => 4,
                            'created_at' => $date]);

                    //在跑批表中插入此次跑批记录
                    $insert_amount_scripts = \DB::table('amount_scripts')
                        ->insert(['order_header_id' => $order->uid,
                            'run_date' => $date,
                            'run_amount' => $spread_amount,
                            'spread_flag' => $spreader->dist,
                            'child_id' => $spreader->path_end_client_id,
                            'client_id' => $spreader->path_begin_client_id]);

                    if($insert_amount_flow && $insert_amount_scripts){
                        //更新用户的资金表
                        $amount = ClientAmount::where('client_id',$spreader->path_begin_client_id);
                        $subtract_amount = $amount->first()->freezing_amount - $spread_amount;
                        $update_amount = $amount->update(['freezing_amount'=>$subtract_amount]);

                        if($update_amount){
                            Order::where('uid',$order->uid)->update(['unfreezing_amount_flag'=>'Y']);
                        }
                    }
                }
            }
        }catch (Exception $e){
            return $e->getMessage();
        }
    }
}
