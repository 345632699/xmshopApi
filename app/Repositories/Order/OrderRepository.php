<?php
/**
 * Created by PhpStorm.
 * User: xu
 * Date: 2018/7/2
 * Time: 16:49
 */

namespace App\Repositories\Order;


use App\Model\Delivery;
use App\Model\Good;
use App\Model\Order;
use App\Model\OrderDetail;
use Carbon\Carbon;

class OrderRepository implements OrderRepositoryInterface
{

    public function createOrderHeader($request,$client_id)
    {
        $order_header_data['client_id'] = $client_id;
        $order_header_data['order_number'] = config('wechat.payment.default.mch_id').time();
        $order_header_data['order_date'] = Carbon::now();
        $order_header_data['open_invoice_flag'] = $request->get('open_invoice_flag','N');
        $order_header = Order::create($order_header_data);
        return $order_header;
    }

    public function createOrderLine($order_header_id,$request)
    {
        $order_line_data['header_id'] = $order_header_id;
        $order_line_data['good_id'] = $request->get('good_id',1);
        $order_line_data['color'] = $request->get('color',"白色");
        $order_line_data['size'] = $request->get('size',"17*17*17cm");
        $order_line_data['unit_price'] = $request->get('unit_price',499);
        $order_line_data['quantity'] = $request->get('quantity',1);
        $order_line = OrderDetail::create($order_line_data);
        return $order_line;
    }

    public function createDelivery($order_header_id,$address_id)
    {
        $delivery_data['order_header_id'] = $order_header_id;
        $delivery_data['delivery_contact_id'] = $address_id;
        $delivery = Delivery::create($delivery_data);
        return $delivery;
    }

    public function getOrderList($order_status)
    {
        if (isset($order_status)){
            $where['order_status'] = $order_status;
            $where['client_id'] = session('client.id');
        }else{
            $where['client_id'] = session('client.id');
        }

        $order_list = \DB::table('order_headers')
            ->leftJoin('order_lines as ol','ol.header_id','=','order_headers.uid')
            ->leftJoin('goods','goods.uid','=','good_id')
            ->where($where)
            ->get()->toArray();

        return $order_list;
    }
}