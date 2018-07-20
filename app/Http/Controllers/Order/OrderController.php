<?php

namespace App\Http\Controllers\Order;

use App\Model\Delivery;
use App\Model\Order;
use App\Repositories\Order\OrderRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{

    public function __construct(OrderRepository $order)
    {
        $this->order = $order;
    }

    public function index(Request $request){
        $order_status = $request->get('order_status',-1);
        $limit = $request->get('limit',10);
        $where = [];
        if ($order_status >= 0){
            $where['order_status'] = $order_status;
        }
        $orderList = Order::select('order_headers.*','color','quantity','size','ol.unit_price','good_id','goods.name','nick_name')
            ->leftJoin('order_lines as ol','order_headers.uid','=','ol.header_id')
            ->leftJoin('goods','goods.uid','=','ol.good_id')
            ->leftJoin('clients','clients.id','=','order_headers.client_id')
            ->where($where)->paginate($limit);
        foreach($orderList as $order){
            //订单状态，见xm_lookup_values表ORDER_STATUS：0-已下单，1-已支付，2-待发货，3-已发货，4-已完成，5-异常，6-申请退货，7-确认退货，8-已退货
            $order_status = $order->order_status;
            switch ($order_status){
                case 0:
                    $order->order_status_name = "未支付";
                    break;
                case 1:
                    $order->order_status_name = "已支付";
                    break;
                case 2:
                    $order->order_status_name = "待发货";
                    break;
                case 3:
                    $order->order_status_name = "已发货";
                    break;
                case 4:
                    $order->order_status_name = "已完成";
                    break;
                case 5:
                    $order->order_status_name = "异常";
                    break;
                case 6:
                    $order->order_status_name = "申请退货";
                    break;
                case 7:
                    $order->order_status_name = "确认退货";
                    break;
                case 8:
                    $order->order_status_name = "已退货";
                    break;
                case 9:
                    $order->order_status_name = "已取消";
                    break;
            }
            // 0-预付款，1-货到付款
            if (!$order->order_type){
                $order->order_type_name = '微信支付';
            }else{
                $order->order_type_name = '货到付款';
            }
        }

        return view('admin.order.index',compact(
            'orderList'
        ));
    }

    public function show($order_id){
        $data = $this->order->getOrderDetail($order_id);
        $order = $data['order'] ;
        $good = $data['good'] ;
        $address = $data['address'] ;
        $delivery = $data['delivery'] ;
        $invoice = $data['invoice'] ;
        $product_ids = implode(',',$data['product_ids']);
        return view('admin.order.detail',compact(
            'order',
            'address',
            'good',
            'delivery',
            'product_ids',
            'invoice')
        );
    }

    public function updateStatus(Request $request){
        $order_status = $request->get('order_status',0);
        $order_id = $request->order_id;
        $order = Order::find($order_id);
        $res = $order->update(['order_status'=>$order_status]);
        return redirect()->route('order.show',$order_id);
    }

    public function editDelivery($order_id){
        return view('admin.order.delivery',compact('order_id'));
    }

    public function updateDelivery(Request $request){
        $delivery_name = $request->name;
        $delivery_number = $request->delivery_number;
        $delivery_number = $delivery_name. " " .$delivery_number;
        $order_id = $request->order_id;
        $delivery = Delivery::where('order_header_id',$order_id)->first();
        $res = $delivery->update(['delivery_number'=>$delivery_number]);

        $product_ids = explode(',',$request->product_ids);
        foreach ($product_ids as $key=>$id){
            $insertData[$key]['delivery_id'] = $delivery->uid;
            $insertData[$key]['product_id'] = $id;
            $insertData[$key]['updated_at'] = Carbon::now();
        }
        \DB::table('delivery_products')->insert($insertData);

        return redirect()->route('order.show',$order_id);

    }
}
