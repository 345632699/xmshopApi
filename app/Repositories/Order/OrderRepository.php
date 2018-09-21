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
use App\Model\ClientCoupons;
use App\Repositories\Client\ClientRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class OrderRepository implements OrderRepositoryInterface
{

    public function __construct(ClientRepository $client)
    {
        $this->client = $client;
    }

    public function createLinkMapping($order_header_id, $request, $parent_id)
    {
        $client_id = session('client.id');
        $link_mapping_data['child_client_id'] = $client_id;
        $link_mapping_data['parent_client_id'] = $parent_id;
        $link_mapping_data['order_id'] = $order_header_id;

        $link_mapping = \DB::table('client_link_mapping')->where('child_client_id', $client_id);
        $is_exist = $link_mapping->count();

        if ($is_exist) {
            if(!$link_mapping->first()->order_id){
                $update_link_mapping = $link_mapping->update(['order_id'=>$order_header_id,'parent_client_id'=>$parent_id]);
            }
        }else{
            $insert_link_mapping = \DB::table('client_link_mapping')
                ->insert($link_mapping_data);
        }

        return $link_mapping->first();
    }

    public function createOrderHeader($request, $client_id)
    {
        $order_header_data['client_id'] = $client_id;
        $order_header_data['contract_id'] = $request->address_id;
        $order_header_data['order_number'] = config('wechat.payment.default.mch_id').time();
        $order_header_data['order_date'] = Carbon::now();
        $order_header_data['open_invoice_flag'] = $request->get('open_invoice_flag', 'N');
        $order_header = Order::create($order_header_data);
        return $order_header;
    }

    public function createOrderLine($order_header_id, $request, $parent_id)
    {
        $client_id = session('client.id');
        //update by cai 20180904 --start
//        $buy_count = Order::whereRaw('client_id = ? AND uid != ? AND ( order_status = 0 OR ( pay_date IS NOT NULL AND return_date IS NULL))', [
//            $client_id,
//            $order_header_id,
//        ])->count();
        $combo_id = $request->get('combo_id', 1);
        $combo = \DB::table("good_combos")->where('uid', $combo_id)->first();
        $order_line_data['header_id'] = $order_header_id;
        $order_line_data['good_id'] = $request->get('good_id', 1);

//        if ($buy_count == 0 && $parent_id > 0) {
//            $price = $combo->unit_price;
//        } else {
//            $price = $combo->original_unit_price;
//        }
        //--end

        $price = $combo->original_unit_price;
        $total_price = $combo->original_unit_price * $request->get('quantity', 1);
        $client_coupon_id = $request->get('client_coupon_id', 0);

        $now = Carbon::now()->toDateTimeString();
        $client_coupon = ClientCoupons::where('uid',$client_coupon_id)->where('expired_date', '>', $now)->first();
        if($client_coupon->status){
            $coupon_amount = $client_coupon->coupon_amount;
            $total_price = $total_price - $coupon_amount;
            $client_coupon->update(['status'=>0]);
        }

        $order_line_data['color'] = $request->get('color', "白色");
        $order_line_data['combo'] = $combo->name;
        $order_line_data['buyer_msg'] = $request->get('buyer_msg', "");
        $order_line_data['quantity'] = $request->get('quantity', 1);
        $order_line_data['unit_price'] = $price;
//        $order_line_data['total_price'] = $price * $order_line_data['quantity'];
        $order_line_data['total_price'] = $total_price;
        $order_line = OrderDetail::create($order_line_data);
        return $order_line;
    }

    public function createDelivery($order_header_id, $address_id)
    {
        $delivery_data['order_header_id'] = $order_header_id;
        $delivery_data['delivery_contact_id'] = $address_id;
        $contract = Contact::find($address_id);
        $delivery_data['address'] = '';
        if ($contract) {
            $delivery_data['address'] = $contract->name . ' ' . $contract->province . $contract->city . $contract->area . $contract->address . " " . $contract->phone_num;
        }
        $delivery = Delivery::create($delivery_data);
        return $delivery;
    }

    public function getOrderList($order_status, $limit = 5)
    {
        if ($order_status >= 0) {
            $where['order_status'] = $order_status;
            $where['client_id'] = session('client.id');
        } else {
            $where['client_id'] = session('client.id');
        }

        if (in_array($order_status, [-2, -1, 0, 3,])) {
            \DB::transaction(function () {
                //关闭到期的待付款订单
                $one_hour_ago = Carbon::now()->subHours(1)->toDateTimeString();
                Order::where(['order_status' => 0])->where('order_date', '<=', $one_hour_ago)->update(['order_status' => 9]);

                //完成待收货的订单
                $now = Carbon::now()->toDateTimeString();
                $ids = Order::where('request_close_date', '<=', $now)->pluck('uid');
                Order::whereIn('uid', $ids)->update([
                    'order_status' => 4,
                    'completion_date' => $now,
                ]);
                Delivery::whereIn('order_header_id', $ids)->update(['delivery_status' => 2]);
            });
        }

        $order_list = \DB::table('order_headers')->select('order_headers.*', 'order_headers.uid as order_id', 'ol.good_id', 'goods.name as good_name', 'goods.thumbnail', 'ol.color', 'ol.combo', 'ol.total_price', 'ol.unit_price', 'ol.quantity', 'ol.robot_id')->leftJoin('order_lines as ol', 'ol.header_id', '=', 'order_headers.uid')->leftJoin('goods', 'goods.uid', '=', 'good_id')->where($where);

        //add by cai 20180830 --start
        if ($order_status == -2) {
            $order_list = $order_list->whereIn('order_status', [1, 3]);
        }
        $order_list = $order_list->latest('order_date')->paginate($limit)->toArray();
        //--end

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
        $invoice['invoice_type'] = $request->get('invoice_type', '0');
        $invoice['detail'] = $request->get('detail', '');
        $invoice['phone_num'] = $request->get('phone_num', null);
        $invoice['amount'] = $total_price;
        $invoice['email'] = $request->get('email', '');
        $invoice['title'] = $request->get('title', '');
        $invoice['tax_code'] = $request->get('tax_code', '');
        $invoice['client_id'] = $client_id;
        $invoice['order_id'] = $order_id;
        $invoice['invoice_date'] = Carbon::now();
        $res = Invoice::create($invoice);
        Log::info("invoice 创建成功");
    }

    public function getOrderDetail($order_id)
    {
        try {
            $order = Order::select('order_headers.*', 'color', 'quantity', 'combo', 'unit_price', 'good_id', 'nick_name', 'buyer_msg')->leftJoin('order_lines as ol', 'order_headers.uid', '=', 'ol.header_id')->leftJoin('clients', 'clients.id', '=', 'order_headers.client_id')->where('order_headers.uid', $order_id)->first();

            //订单状态，见xm_lookup_values表ORDER_STATUS：0-待支付，1-已付款，2-待发货，3-已发货，4-已完成，5-异常，6-申请退货，7-确认退货，8-已退货 9-已关闭
            $order_status = $order->order_status;
            switch ($order_status) {
                case 0:
                    $order->order_status_name = "待支付";
                    break;
                case 1:
                    $order->order_status_name = "已付款";
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
                    $order->order_status_name = "已关闭";
                    break;
            }
            // 0-预付款，1-货到付款
            if (!$order->order_type) {
                $order->order_type_name = '微信支付';
            } else {
                $order->order_type_name = '货到付款';
            }


            $good = Good::find($order->good_id);
            $address = Contact::where('uid', $order->contract_id)->first();
            $delivery = Delivery::select('delivery_products.product_id', 'delivery.*')->leftJoin('delivery_products', 'delivery_id', '=', 'delivery.uid')->where('order_header_id', $order->uid)->first();
            $product_ids = Delivery::select('delivery_products.product_id')->rightJoin('delivery_products', 'delivery_id', '=', 'delivery.uid')->where('order_header_id', $order->uid)->pluck('product_id')->toArray();
            $invoice = \DB::table('invoice_record')->where('order_id', $order->uid)->first();
            if (!$invoice)
                $invoice = [];
            $data['order'] = $order;
            $data['good'] = $good;
            $data['address'] = $address;
            $data['delivery'] = $delivery;
            $data['invoice'] = $invoice;
            $data['product_ids'] = $product_ids;
            return $data;
        } catch (Exception $e) {
            return $e->getMessage();
        }

    }

    /**
     * @param $order_id
     * @param $client_id
     * @return mixed
     * 确认收货
     */
    public function confirm($order_id, $client_id)
    {
        if (is_null($order_id)) {
            return response_format([], 0, '缺少order_id参数', 400);
        }
        try {
            DB::beginTransaction();
            Order::where([
                'uid' => $order_id,
                'client_id' => $client_id,
            ])->update(['order_status' => 4]);

            Delivery::where('order_header_id', $order_id)->update(['delivery_status' => 2]);

            DB::commit();
            return [
                'status' => 1,
                'statusCode' => 200,
                'msg' => 'success',
            ];
        } catch (Exception $e) {
            DB::rollback();
            return [
                'status' => 0,
                'statusCode' => $e->getCode(),
                'msg' => $e->getMessage(),
            ];
        }
    }

    /**
     * @param $order_id
     * @param $client_id
     * @return mixed
     * 取消订单
     */
    public function cancel($request, $client_id)
    {
        $order_id = intval($request->order_id);
        if (is_null($order_id)) {
            return response_format([], 0, '缺少order_id参数', 400);
        }


        try {
            $order = Order::where([
                'uid' => $order_id,
                'client_id' => $client_id,
                'order_status' => 0,
            ])->first();
            if (!$order) {
                return [
                    'status' => 0,
                    'statusCode' => 400,
                    'msg' => '订单不存在',
                ];
            }

            $res = $order->update([
                'order_status' => 9,
                'cancel_reason' => htmlspecialchars($request->cancel_reason),
            ]);
            if ($res) {
                return [
                    'status' => 1,
                    'statusCode' => 200,
                    'msg' => 'success',
                ];
            } else {
                return [
                    'status' => 0,
                    'statusCode' => 500,
                    'msg' => 'error',
                ];
            }
        } catch (Exception $e) {
            return [
                'status' => 0,
                'statusCode' => $e->getCode(),
                'msg' => $e->getMessage(),
            ];
        }
    }
}