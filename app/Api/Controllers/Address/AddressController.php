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

    public function index() {
        $client_id = session('client.id');
        $address_list = Contact::where('client_id',$client_id)->get()->toArray();
        return response_format($address_list);
    }

    public function get($id) {
        $address = Contact::find($id)->first();
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
     * @apiSuccess {String} firstname Firstname of the User.
     * @apiSuccess {String} lastname  Lastname of the User.
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *       "firstname": "John",
     *       "lastname": "Doe"
     *     }
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
     * @api {post} /address/{id}/edit 编辑收货地址
     * @apiName AddressEdit
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

    public function edit($id,Request $request){
        $input = $request->input();
        $address = Contact::find($id);
        $res = $address->update($input);
        if ($res) {
            return response_format([],1,'更新成功');
        }else{
            return response_format([],0,'暂无更新');
        }
    }

}
