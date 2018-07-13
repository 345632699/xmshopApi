<?php

namespace App\Api\Controllers\Address;

use App\Api\Controllers\BaseController;
use App\Model\Contact;
use App\Repositories\Client\ClientRepository;
use Illuminate\Http\Request;
use Mockery\Exception;

class AddressController extends BaseController
{
    private $client;
    public function __construct(ClientRepository $client)
    {
        $this->client = $client;
    }

    /**
     * @api {get} /address 获取地址列表
     * @apiName AddressList-获取地址列表
     * @apiGroup Address
     *
     * @apiHeader (Authorization) {String} authorization Authorization value.
     *
     *
     * @apiSuccess {Array} data 返回的数据结构体
     * @apiSuccess {Number} status  1 执行成功 0 为执行失败
     * @apiSuccess {string} msg 执行信息提示
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *     }
     *
     */
    public function index(Request $request) {
        $limit = $request->get('limit',5);
        $client = $this->client->getUserByOpenId();
        $client_id = $client->id;
        $address_list = Contact::where('client_id',$client_id)->paginate($limit)->toArray();
        return response_format($address_list);
    }


    /**
     * @api {get} /address/get 获取地址详情
     * @apiName AddressGet-获取地址详情
     * @apiGroup Address
     *
     * @apiHeader (Authorization) {String} authorization Authorization value.
     *
     * @apiParam {int} address_id 地址id
     *
     * @apiSuccess {Array} data 返回的数据结构体
     * @apiSuccess {Number} status  1 执行成功 0 为执行失败
     * @apiSuccess {string} msg 执行信息提示
     *
     *
     */
    public function get(Request $request) {
        $address_id = $request->address_id;
        if (!$address_id){
            return response_format(['err_msg'=>'地址ID 不能为空']);
        }
        $address = Contact::find($address_id)->first();
        return response_format($address);
    }

    /**
     * @api {post} /address/create 创建收货地址
     * @apiName AddressCreate
     * @apiGroup Address
     *
     * @apiHeader (Authorization) {String} authorization Authorization value.
     *
     * @apiParam {string} name 用户姓名
     * @apiParam {number} phone_num 手机号码
     * @apiParam {string} province 省
     * @apiParam {string} city 市
     * @apiParam {string} area 区
     * @apiParam {string} address 详细地址 不能为空
     * @apiParam {string} default_flag 是否默认 Y 默认 N 不默认
     *
     * @apiSuccess {Array} data 返回的数据结构体
     * @apiSuccess {Number} status  1 执行成功 0 为执行失败
     * @apiSuccess {string} msg 执行信息提示
     *
     */
    public function create(Request $request){
        $client = $this->client->getUserByOpenId();
        $client_id = $client->id;
        $input = $request->input();
        $input['client_id'] = $client_id;
        $res = Contact::create($input);
        if ($res){
            return response_format($res->toArray());
        }
    }

    /**
     * @api {post} /address/edit 编辑收货地址
     * @apiName AddressEdit
     * @apiGroup Address
     *
     * @apiHeader (Authorization) {String} authorization Authorization value.
     *
     * @apiParam {int} address_id 地址ID
     * @apiParam {string} name 用户姓名
     * @apiParam {number} phone_num 手机号码
     * @apiParam {string} province 省
     * @apiParam {string} city 市
     * @apiParam {string} area 区
     * @apiParam {string} address 详细地址 不能为空
     * @apiParam {string} default_flag 是否默认 Y 默认 N 不默认
     *
     * @apiSuccess {Array} data 返回的数据结构体
     * @apiSuccess {Number} status  1 执行成功 0 为执行失败
     * @apiSuccess {string} msg 执行信息提示
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *          "response": {
     *               "data": [],
     *               "status": 1,
     *               "msg": "更新成功"
     *          }
     *     }
     *
     */

    public function edit(Request $request){
        $address_id = $request->address_id;
        $input = $request->input();
        $address = Contact::find($address_id);
        $res = $address->update($input);
        if ($res) {
            return response_format([],1,'更新成功');
        }else{
            return response_format([],0,'暂无更新');
        }
    }

}
