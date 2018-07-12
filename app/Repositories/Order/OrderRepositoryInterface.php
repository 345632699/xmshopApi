<?php
/**
 * Created by PhpStorm.
 * User: xu
 * Date: 2018/7/2
 * Time: 16:47
 */

namespace App\Repositories\Order;


interface OrderRepositoryInterface {

    public function createOrderHeader($request,$client_id);

    public function createOrderLine($order_header_id,$request);

    public function createDelivery($order_header_id,$address_id);

    /**
     * @param $client_id 用户ID
     * @param $order_id  订单ID
     * @param $request   请求参数
     * @return mixed
     *
     * 创建发票信息
     */
    public function createInvoice($client_id,$order_id,$total_price,$request);

    public function getOrderList($order_status);

    public function getOrderDetail($order_id);
}