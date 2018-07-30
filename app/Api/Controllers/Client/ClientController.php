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

}
