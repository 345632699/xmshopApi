<?php

namespace App\Api\Controllers\Pay;

use App\Api\Controllers\BaseController;
use App\Model\Client;
use App\Model\Order;
use App\Repositories\Client\ClientRepository;
use App\Repositories\Pay\PayRepository;
use Carbon\Carbon;
use EasyWeChat\Payment\Application;
use Illuminate\Http\Request;


class PayController extends BaseController
{

    public function __construct(ClientRepository $client,PayRepository $pay)
    {
        $this->client = $client;
        $this->pay = $pay;
    }

    public function index() {

    }

    public function create(){

    }

    public function payNotify() {
        $app = app('wechat.payment');
        $response = $app->handlePaidNotify(function ($message, $fail) {
            // 你的逻辑
            $out_trade_no = $message->out_trade_no;
            $pay_bills = \DB::table("pay_bills")->where('pay_order_number',$out_trade_no)->fisrt();
            if (!$pay_bills) { // 如果订单不存在
                return 'Order not exist.'; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }
            if ($pay_bills->pay_date) { // 假设订单字段“支付时间”不为空代表已经支付
                return true; // 已经支付成功了就不再更新了
            }

            if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                // 用户是否支付成功
                if (array_get($message, 'result_code') === 'SUCCESS') {
                    $update = [
                        'pay_date' => Carbon::now(),
                        'pay_status' => 1
                    ];
                    $res = $pay_bills->update($update);
                    if ($res){
                        $parent_id = $res->parent_id;
                        $client_id = $res->client_id;
                        $this->client->updateTreeNode($client_id,$parent_id);
                    }
                    $order = Order::find($pay_bills->order_header_id);
                    if ($order){
                        return $fail('订单不存在');
                    }
                    $orderUpdate['pay_date'] = Carbon::now();
                    $orderUpdate['order_status'] = 1;
                    $order->update($orderUpdate);
                // 用户支付失败
                } elseif (array_get($message, 'result_code') === 'FAIL') {
                    $update = [
                        'pay_status' => 2
                    ];
                    $res = $pay_bills->update($update);
                }
            } else {
                return $fail('通信失败，请稍后再通知我');
            }

            return true;
        });

        return $response;
    }

    public function withdraw(Request $request){
        $withdraw_amount = $request->amount;
        $client = $this->client->getUserByOpenId();
        $client_id = $client->id;
        $amount = \DB::table('client_amount')->where('client_id',$client_id)->first();
        if ($amount){
            $can_withdraw_amount = $amount->amount - $amount->freezing_amount;
            if ( $can_withdraw_amount > $withdraw_amount){
                //record
                $withdraw_record['client_id'] = $client_id;
                $withdraw_record['partner_trade_no'] = 'W'.time();
                $withdraw_record['amount'] = $can_withdraw_amount;
                $withdraw_record['created_at'] = Carbon::now();
                $withdraw_record['updated_at'] = Carbon::now();

                $res = \DB::table('withdraw_record')->create($withdraw_record);
                if ($res->uid) {
                    $update['amount'] = $amount->amount - $withdraw_amount;
                    $amount->update($update);
                    $this->pay->withDraw($res->uid,$client,$amount);
                }

                return response_format($res);

            }else{
                return response_format([],0,'可提余额不足');
            }
        }else{
            return response_format([],0,'个人新系获取失败');
        }
    }


    /**
     * @api {post} /pay/withdraw_list 取消订单
     * @apiName PayWithdraw
     * @apiGroup Pay
     *
     * @apiHeader (Authorization) {String} authorization Authorization value.
     *
     * @apiParam {int} type 1 获取个人的  2 获取所有的
     * @apiParam {int} status 0 提现失败 1 提现成功 2 提现中
     *
     * @apiSuccess {Array} data 返回的数据结构体
     * @apiSuccess {Number} status  1 执行成功 0 为执行失败
     * @apiSuccess {string} msg 执行信息提示
     *
     *
     */
    public function getWithDrawRecordList(Request $request){
        //type - 1 获取个人的  2 获取所有的
        $type = $request->get('type',1);
        $client_id = session('client.id');

        //status - 0 提现失败 1 提现成功 2 提现中
        $status = $request->status;

        $limit = $request->get('limit',5);

        if ($type == 1) {
            if ($status){
                $where['status'] = $status;
                $where['client_id'] = $client_id;
            }else{
                $where['client_id'] = $client_id;
            }
            $list = \DB::table('withdraw_record')
                        ->select('withdraw_record.*','clients.nick_name','clients.phone_num')
                        ->leftJoin('clients','clients.id','=','withdraw_record.client_id')
                        ->where($where)->paginate($limit);
            return response_format($list);
        }else{
            $list = \DB::table('withdraw_record')
                ->select('withdraw_record.*','clients.nick_name','clients.phone_num')
                ->leftJoin('clients','clients.id','=','withdraw_record.client_id')
                ->where('status',$status)
                ->orderBy('uid','desc')
                ->limit(8)
                ->get()->toArray();
            return response_format($list);
        }
    }


}
