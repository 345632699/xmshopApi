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
use Illuminate\Support\Facades\Log;

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
        $payBill['notify_url'] = 'https://wxapp.honeybot.cn/api/pay/notify';
        $payBill['trade_type'] = 'JSAPI';
        $payBill['openid'] = $open_id;
        $result = $app->order->unify($payBill);
        $prepay_id =  $result['prepay_id'];
        $wxpayJssdkConfig = $app->jssdk->sdkConfig($prepay_id);
        return $wxpayJssdkConfig;
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

    public function withDraw($withdraw_id,$client,$amount)
    {
        $withdraw_detail = \DB::table('withdraw_record')->where(['uid'=>$withdraw_id,'status'=>2])->first();
        if ($withdraw_detail){
            $app = app('wechat.payment');
            $res = $app->transfer->toBalance([
                'partner_trade_no' => $withdraw_detail->partner_trade_no, // 商户订单号，需保持唯一性(只能是字母或者数字，不能包含有符号)
                'openid' => $client->open_id,
                'check_name' => 'NO_CHECK', // NO_CHECK：不校验真实姓名, FORCE_CHECK：强校验真实姓名
                're_user_name' => '', // 如果 check_name 设置为FORCE_CHECK，则必填用户真实姓名
                'amount' => $withdraw_detail->amount * 100, // 企业付款金额，单位为分
                'desc' => '奖金提现', // 企业付款操作说明信息。必填
            ]);
            Log::info('=====微信提现=====');
            Log::info("微信提现返回结果：".json_encode($res));
            Log::info('=====微信提现=====');
            //update by cai 20180823
            //update start
            if ($res['return_code'] == 'SUCCESS' && $res['result_code'] == 'SUCCESS'){
            //update end
                \DB::table('withdraw_record')->where(['uid'=>$withdraw_id,'status'=>2])->update(['status'=>1,'success_time'=>Carbon::now()]);
            }else{
                //付款失败
                \DB::table('withdraw_record')->where(['uid'=>$withdraw_id,'status'=>2])->update(['status'=>0]);
                $all_amount = $amount->amount + $withdraw_detail->amount;
                $amount->update(['amount'=>$all_amount]);
            }
        }
    }
}