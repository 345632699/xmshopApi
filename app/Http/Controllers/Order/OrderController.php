<?php

namespace App\Http\Controllers\Order;

use App\Model\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index(Request $request){
        $order_status = $request->get('order_status',-1);
        $limit = $request->get('limit',10);
        $where = [];
        if ($order_status >= 0){
            $where['order_status'] = $order_status;
        }
        $orderList = Order::where($where)->paginate($limit);
        return view('admin.order.index',compact(
            'orderList'
        ));
    }
}
