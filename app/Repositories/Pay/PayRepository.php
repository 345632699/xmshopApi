<?php
/**
 * Created by PhpStorm.
 * User: xu
 * Date: 2018/7/2
 * Time: 16:49
 */

namespace App\Repositories\Pay;


use App\Model\Good;
use App\Model\Order;
use Carbon\Carbon;
use EasyWeChat\Payment\Application;

class PayRepository implements PayRepositoryInterface
{
    public function getPayJssdk($pay,$open_id)
    {
        $a = new Application(config('wechat.payment'));
        $app = app('wechat.payment');
        //构造微信支付数组
        $payBill['body'] = '小萌商城';
        $payBill['out_trade_no'] = $pay['pay_order_number'];
        $payBill['total_fee'] = $pay['total_price'];
        $payBill['spbill_create_ip'] = '';
        $payBill['notify_url'] = '';
        $payBill['trade_type'] = 'JSAPI';
        $payBill['openid'] = $open_id;
        dd($app->jssdk->sdkConfig("wx201411102639507cbf6ffd8b0779950874"));
        $result = $app->order->unify($payBill);
    }

    public function createPayBillByOrder($order_header_id, $client,$parent_id)
    {
        $order = Order::select('order_headers.uid','ol.quantity','ol.unit_price')
            ->leftJoin('order_lines as ol','ol.header_id','=','order_headers.uid')
            ->where('order_headers.uid',$order_header_id)
            ->first();
        if ($order){
            $pay['client_id'] = $client->id;
            $pay['parent_id'] = $parent_id;
            $pay['order_header_id'] = $order->uid;
            $pay['pay_order_number'] = config('wechat.payment.default.mch_id').time();
            $pay['total_price'] = $order->quantity * $order->unit_price * 100;
            $pay['created_at'] = Carbon::now();
            $pay['updated_at'] = Carbon::now();
            $pay_bill_id = \DB::table('pay_bills')->insertGetId($pay);
            if ($pay_bill_id){
                return $pay;
            }
        }
    }
}