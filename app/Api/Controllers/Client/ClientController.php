<?php

namespace App\Api\Controllers\Client;

use App\Api\Controllers\BaseController;
use App\Model\Client;
use App\Model\ClientCoupons;
use App\Model\Order;
use Carbon\Carbon;
use App\Repositories\Client\ClientRepository;
use Illuminate\Http\Request;


class ClientController extends BaseController
{

    private $client;
    public function __construct(ClientRepository $client)
    {
        $this->client = $client;
    }

    /**
     * @api {get} /client 用户详情
     * @apiName 用户详情
     * @apiGroup Client
     *
     * @apiHeader (Authorization) {String} authorization Authorization value.
     *
     */
    public function index() {
        $client_id = $this->client->getUserByOpenId()->id;
        $client = Client::select('nick_name','clients.phone_num','avatar_url','amount','freezing_amount')
                        ->leftJoin('client_amount','client_id','=','clients.id')
                        ->where('id',$client_id)
                        ->first();
        $address_id = \DB::table('client_delivery_contact')
                        ->where('client_id',$client_id)
                        ->Where('default_flag','Y')
                        ->first();
        $client->default_address_id = $address_id;

        //add by cai 20180904 --start
        //待支付
        $wait_pay_close = Carbon::now()->modify('-1 hours');
        $wait_pay = Order::where(['client_id'=>$client_id,'order_status'=>0])->where('order_date','>',$wait_pay_close)->count();
        $client->wait_pay = intval($wait_pay);

        //待收货和已付款
        $wait_delivery_close = Carbon::now()->modify('-10 days');
        $wait_delivery = Order::whereRaw('client_id = ? AND ( order_status = 1  OR ( order_status = 3 AND order_date > ?))',[$client_id,$wait_delivery_close])->count();
        $client->wait_delivery = intval($wait_delivery);
        //--end

        return response_format($client);
    }

    /**
     * 更新父子节点信息
     */
    public function updateTreeNode(Request $request){

    }

    /**
     * @api {get} /client/check 是否绑定机器人
     * @apiName 是否绑定机器人
     * @apiGroup Client
     *
     * @apiHeader (Authorization) {String} authorization Authorization value.
     *
     */
    public function checkBind(){
        $client_id = $this->client->getUserByOpenId()->id;
        $result = $this->client->checkBind($client_id);
        if ($result){
            return response_format(['has_bind_robot'=>1]);
        }else{
            return response_format(['has_bind_robot'=>0]);
        }
    }
    //add by cai 20180904 --start
    /**
     * @api {get} /client/share 是否可分享
     * @apiName 是否可分享
     * @apiGroup Client
     *
     * @apiHeader (Authorization) {String} authorization Authorization value.
     *
     */
    public function checkShare(){
        $client_id = $this->client->getUserByOpenId()->id;
        $result = $this->client->checkShare($client_id);
        if ($result){
            return response_format(['can_share'=>1]);
        }else{
            return response_format(['can_share'=>0]);
        }
    }

    /**
     * @api {get} /client/first 是否首次购买
     * @apiName 是否首次购买
     * @apiGroup Client
     *
     * @apiHeader (Authorization) {String} authorization Authorization value.
     *
     */
    public function checkFirstBuy(){
        $client_id = $this->client->getUserByOpenId()->id;
        $result = $this->client->checkFirstBuy($client_id);
        if ($result){
            return response_format(['is_first_buy'=>1]);
        }else{
            return response_format(['is_first_buy'=>0]);
        }
    }
    //--end
    /**
     * @api {get} /client/flow_list 资金变更流水
     * @apiName 资金变更流水
     * @apiGroup Client
     *
     * @apiHeader (Authorization) {String} authorization Authorization value.
     *
     * @apiParam {int} type 提现类型 1 增加冻结金额 2 可提现金额减少 3 减少冻结金额 4 可提现金额增加
     * @apiParam {int} limit 返回条数
     *
     */
    public function getFlowList(Request $request){
        $limit = $request->get('limit',20);
        $client_id = $this->client->getUserByOpenId()->id;
        $type = $request->get('type',0);
        $where['client_id'] = $client_id;
        if ($type) {
            $where['type'] = $type;
        }
        $flow_list = \DB::table('client_amount_flow')
            ->select('clients.nick_name as child_name','client_amount_flow.*')
            ->leftJoin('clients','clients.id','=','child_id')
            ->where($where)
            ->orderBy('uid','desc')
            ->paginate($limit);
        return response_format($flow_list);
    }

    //add by cai 20180918 --start

    /**
     * @api {post} /client/get_spread_coupon 领取优惠券
     * @apiName GetSpreadCoupon
     * @apiGroup client
     *
     * @apiHeader (Authorization) {String} authorization Authorization value.
     *
     * @apiParam {int} parent_id 推广人ID
     *
     * @apiSuccess {int} result_flag 0-异常 1-领取成功  2-不成功，已享受过新手优惠  3-不成功，存在未使用的优惠券  4-不成功，已经领取过该优惠券
     *
     */
    public function getSpreadCoupon(Request $request){
        $client_id = $this->client->getUserByOpenId()->id;
        $client_coupon = ClientCoupons::where('client_id',$client_id);
        $result_flag = 0;//异常

        if($client_coupon->get()){//存在优惠券
            $is_new_client = $client_coupon->where('status',0)->get()->count();
            if($is_new_client){//已经使用过优惠券
                $result_flag = 2;
            }
            else{//还没有使用过优惠券
                $is_taken = ClientCoupons::where(['client_id'=>$client_id,'spreader_id'=>$request->parent_id])->get()->count();
                if($is_taken){//已经通过这个推广者的链接领取过优惠券
                    $result_flag = 4;
                }
                else{//没有通过这个推广者的链接领取过优惠券
                    $now = Carbon::now()->toDateTimeString();
                    $is_has_coupon = ClientCoupons::where('client_id',$client_id)->where('expired_date', '>', $now)->get()->count();
                    if($is_has_coupon){//存在未过期的可使用的优惠券
                        $result_flag = 3;
                    }
                    else{
                        $result = $this->client->getSpreadCoupon($request,$client_id);
                        if($result) $result_flag = 1;
                    }
                }
            }
        }
        else{//不存在优惠券
            $result = $this->client->getSpreadCoupon($request,$client_id);
            if($result) $result_flag = 1;
        }
        return response_format(['result_flag'=>$result_flag]);
    }

    /**
     * @api {get} /client/get_coupon_list 获取优惠券列表
     * @apiName GetCouponList
     * @apiGroup client
     *
     * @apiHeader (Authorization) {String} authorization Authorization value.
     *
     * @apiParam {int} type 优惠券类型 1 有效  2 已失效  -1 全部
     * @apiParam {int} limit 返回条数 不传默认20
     *
     * @apiSuccess {Array} data 优惠券列表
     * @apiSuccess {int} uid 用户优惠券id
     * @apiSuccess {string} coupon_amount 优惠券优惠金额
     * @apiSuccess {string} expired_date 券到期时间
     * @apiSuccess {int} coupon_id 优惠券id
     * @apiSuccess {int} client_id 用户id
     * @apiSuccess {int} spreader_id 推广人id
     * @apiSuccess {int} status 使用状态 1-可使用，0-已使用
     * @apiSuccess {string} description 优惠券说明
     * @apiSuccess {int} type 优惠券类型 1-优惠券，2-折扣券，3-满减券
     * @apiSuccess {int} expired_day 券有效天数
     *
     */
    public function getCouponList(Request $request){
        $client_id = $this->client->getUserByOpenId()->id;
        $coupon_list = $this->client->getCouponList($request,$client_id);
        return response_format($coupon_list);
    }

    //--end
}
