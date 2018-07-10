<?php
/**
 * Created by PhpStorm.
 * User: xu
 * Date: 2018/7/2
 * Time: 16:47
 */

namespace App\Repositories\Pay;


interface PayRepositoryInterface {

    /**
     * @param $pay
     * @param $open_id
     * @return mixed
     * 生成微信支付的相应的js配置
     */
    public function getPayJssdk($pay,$open_id);

    /**
     * @param $order_header_id
     * @param $client
     * @param $parent_id 推广的父级ID
     * @return mixed
     * 根据order_header_id生成支付订单
     */
    public function createPayBillByOrder($order_header_id,$client,$parent_id);
}