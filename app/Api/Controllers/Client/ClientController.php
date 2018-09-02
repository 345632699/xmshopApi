<?php

namespace App\Api\Controllers\Client;

use App\Api\Controllers\BaseController;
use App\Model\Client;
use App\Model\Delivery;
use App\Model\Order;
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
                        ->get()->first();
        $address_id = \DB::table('client_delivery_contact')
                        ->where('client_id',$client_id)
                        ->Where('default_flag','Y')
                        ->first();
        $client->default_address_id = $address_id;

        //待支付
        $wait_pay = Order::where(['client_id'=>$client_id,'order_status'=>0])->count();
        $client->wait_pay = intval($wait_pay);

        //待收货和已付款
        $wait_delivery = Order::where('client_id',$client_id)->whereIn('order_status',[1,3])->count();
        $client->wait_delivery = intval($wait_delivery);

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
            return response_format(['has_bind_robot'=>0],0);
        }
    }

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
            ->limit($limit)->get();
        return response_format($flow_list);
    }

}
