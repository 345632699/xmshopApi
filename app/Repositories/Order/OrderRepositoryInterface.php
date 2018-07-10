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

    public function getOrderList($order_status);
}