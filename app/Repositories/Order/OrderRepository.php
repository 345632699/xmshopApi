<?php
/**
 * Created by PhpStorm.
 * User: xu
 * Date: 2018/7/2
 * Time: 16:49
 */

namespace App\Repositories\Order;


use App\Model\Contact;
use App\Model\Delivery;
use App\Model\Good;
use App\Model\Invoice;
use App\Model\Order;
use App\Model\OrderDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class OrderRepository implements OrderRepositoryInterface
{

    public function createOrderHeader($request,$client_id)
    {
        $order_header_data['client_id'] = $client_id;
        $order_header_data['contract_id'] = $request->address_id;
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
        $good = Good::find($order_line_data['good_id'])->first();
        $order_line_data['color'] = $request->get('color',"白色");
        $order_line_data['size'] = $request->get('size',"17*17*17cm");
        $order_line_data['quantity'] = $request->get('quantity',1);
        $order_line_data['unit_price'] = $good->unit_price;
        $order_line_data['total_price'] = $good->unit_price * $order_line_data['quantity'];
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

    public function getOrderList($order_status, $limit = 5)
    {
        if ($order_status >= 0 ){
            $where['order_status'] = $order_status;
            $where['client_id'] = session('client.id');
        }else{
            $where['client_id'] = session('client.id');
        }


        $order_list = \DB::table('order_headers')
            ->select('order_headers.*','order_headers.uid as order_id','ol.good_id','goods.name as good_name','ol.color','ol.size','ol.total_price','ol.unit_price','ol.quantity','ol.robot_id')
            ->leftJoin('order_lines as ol','ol.header_id','=','order_headers.uid')
            ->leftJoin('goods','goods.uid','=','good_id')
            ->where($where)
            ->paginate($limit)->toArray();

        return $order_list;
    }

    /**
     * @param $client_id 用户ID
     * @param $order_id  订单ID
     * @param $request   请求参数
     * @return mixed
     *
     * 创建发票信息
     */
    public function createInvoice($client_id, $order_id, $total_price, $request)
    {
        //invoice_type 0-个人，1-公司
        $invoice['invoice_type'] = $request->get('invoice_type','0');
        $invoice['detail'] = $request->get('detail','');
        $invoice['amount'] = $total_price;
        $invoice['email'] = $request->get('email','');
        $invoice['title'] = $request->get('title','');
        $invoice['tax_code'] = $request->get('tax_code','');
        $invoice['client_id'] = $client_id;
        $invoice['order_id'] = $order_id;
        $invoice['invoice_date'] = Carbon::now();
        $res = Invoice::create($invoice);
        Log::info("invoice 创建成功");
    }

    public function getOrderDetail($order_id)
    {
        try{
            $order = Order::select('order_headers.*','color','quantity','size','unit_price','good_id')
                ->leftJoin('order_lines as ol','order_headers.uid','=','ol.header_id')
                ->where('order_headers.uid',$order_id)->first();

            //订单状态，见xm_lookup_values表ORDER_STATUS：0-已下单，1-已支付，2-待发货，3-已发货，4-已完成，5-异常，6-申请退货，7-确认退货，8-已退货
            $order_status = $order->order_status;
            switch ($order_status){
                case $order_status == 0:
                    $order->order_status_name = "未支付";
                    break;
                case $order_status == 1:
                    $order->order_status_name = "已支付";
                    break;
                case $order_status == 2:
                    $order->order_status_name = "待发货";
                    break;
                case $order_status == 3:
                    $order->order_status_name = "已发货";
                    break;
                case $order_status == 4:
                    $order->order_status_name = "已完成";
                    break;
                case $order_status == 5:
                    $order->order_status_name = "异常";
                    break;
                case $order_status == 6:
                    $order->order_status_name = "申请退货";
                    break;
                case $order_status == 7:
                    $order->order_status_name = "确认退货";
                    break;
                case $order_status == 8:
                    $order->order_status_name = "已退货";
                    break;
            }
            // 0-预付款，1-货到付款
            if ($order->order_type){
                $order->order_type_name = '微信支付';
            }else{
                $order->order_type_name = '货到付款';
            }


            $good = Good::find($order->good_id);
            $address = Contact::where('uid',$order->contract_id)->first();
            $delivery = Delivery::where('order_header_id',$order->uid)->first();
            $invoice = \DB::table('invoice_record')->where('order_id',$order->uid)->first();
            if (!$invoice)
                $invoice = [];
            $data['order'] = $order;
            $data['good'] = $good;
            $data['address'] = $address;
            $data['delivery'] = $delivery;
            $data['invoice'] = $invoice;
            return $data;
        }catch (Exception $e){
            return $e->getMessage();
        }

    }
}