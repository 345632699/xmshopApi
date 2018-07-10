<?php

namespace App\Api\Controllers\Order;

use App\Api\Controllers\BaseController;
use App\Model\Delivery;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Repositories\Client\ClientRepository;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Pay\PayRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mockery\Exception;


class OrderController extends BaseController
{

    private $client;
    private $pay;
    private $order;

    public function __construct(
        ClientRepository $client,
        PayRepository $pay,
        OrderRepository $order
    ){
        $this->client = $client;
        $this->pay = $pay;
        $this->order = $order;
    }

    public function index() {

    }

    public function get($id){

    }

    /**
     * @param $order_status
     * @return array
     * 订单状态，ORDER_STATUS：0-已下单，1-已支付，2-待发货，3-已发货，4-已完成，5-异常，6-申请退货，7-确认退货，8-已退货
     */
    public function getOrderList($order_status){

        $order_list = $this->order->getOrderList($order_status);
        return response_format($order_list);
    }

    public function create(Request $request){
        $client = $this->client->getUserByOpenId();
        $client_id = $client->id;
        $parent_id = $request->parent_id;
        //判断是否存在订单 存在则不重新新建
        if ($request->order_header_id > 0){
            $payJssdk = $this->getPayJssdk($request->order_header_id,$client,$parent_id);
            return response_format($payJssdk);
        }

        if (is_null($request->address_id)){
            return response_format([],0,'请选择地址');
        }
        //k可以加事務
        try{
            //添加order头
            $order_header = $this->order->createOrderHeader($request,$client_id);
            $order_header_id = $order_header->uid;
            if ($order_header_id) {
                //添加order详情
                $order_line = $this->order->createOrderLine($order_header->uid,$request);

                //添加发货记录
                $delivery = $this->order->createDelivery($order_header->uid,$request->get('address_id'));

                //生成微信支付订单 并 返回支付相关的JS配置
                if ($order_line && $delivery){
                    $payJssdk = $this->getPayJssdk($order_header_id,$client,$parent_id);
                    return response_format($payJssdk);
                }
            }
        }catch (Exception $e){
            return response_format([],0,$e->getMessage());
        }
    }

    public function getPayJssdk($order_header_id,$client,$parent_id){
        $pay = $this->pay->createPayBillByOrder($order_header_id,$client,$parent_id);
        if ($pay){
            $payJssdk = $this->pay->getPayJssdk($pay,$client->open_id);
            return $payJssdk;
        }else{
            return response_format([],0,"订单生成失败");
        }
    }
}
