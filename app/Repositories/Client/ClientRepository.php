<?php
/**
 * Created by PhpStorm.
 * User: xu
 * Date: 2018/7/2
 * Time: 16:49
 */

namespace App\Repositories\Client;
use App\Model\Order;
use App\Model\Client;
use App\Model\ClientAmount;
use Carbon\Carbon;
use JWTAuth;
use Mockery\Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class ClientRepository implements ClientRepositoryInterface
{
    public function selectAll()
    {
    }

    public function find($id)
    {
    }

    public function getUserByOpenId()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                $res['eroor'] = 1;
                $res['msg'] = 'user_not_found';
                return $res;
            }
        } catch (TokenExpiredException $e) {
            $res['eroor'] = 1;
            $res['msg'] = 'token_expired';
            $res['status_code'] = $e->getStatusCode();
            return $res;
        } catch (TokenInvalidException $e) {
            $res['eroor'] = 1;
            $res['msg'] = 'token_invalid';
            $res['status_code'] = $e->getStatusCode();
            return $res;
        } catch (JWTException $e) {
            $res['eroor'] = 1;
            $res['msg'] = 'token_absent';
            $res['status_code'] = $e->getStatusCode();
            return $res;
        }
        // the token is valid and we have found the user via the sub claim
        return $user;
    }

    /**
     * @param $client_id 当前用户ID
     * @param $parent_id  推广人用户ID
     * @return mixed
     *
     * 更新叶子节点信息
     */
    public function updateTreeNode($client_id, $parent_id)
    {
        try{
            $res = \DB::table('client_link_treepaths')->where('path_begin_client_id',$client_id)->count();
            if (!$res){
                if ($parent_id > 0){
                    //更新父节点的 叶子节点改为0
                    $parent_nodes = \DB::table('client_link_treepaths')->where('path_end_client_id',$parent_id);
                    $parent_nodes_id = $parent_nodes->pluck('uid')->toArray();
                    \DB::table('client_link_treepaths')->whereIn('uid',$parent_nodes_id)->update(['is_leaf'=>0]);

                    //插入
                    $insert = [];
                    foreach ($parent_nodes->get() as $key=>$node){
                        $insert[$key]['path_begin_client_id'] = $node->path_begin_client_id;
                        $insert[$key]['dist'] = $node->dist + 1;
                        $insert[$key]['is_leaf'] = 1;
                        $insert[$key]['path_end_client_id'] = $client_id;
                        $insert[$key]['created_at'] = Carbon::now();
                        $insert[$key]['updated_at'] = Carbon::now();
                    }
                    \DB::table('client_link_treepaths')->insert($insert);
                    //插入自身的一条记录
                    $this->insertSelfNode($client_id);

                    \Log::info("======pid:".$parent_id.";cid:".$client_id.",准备添加冻结金额======");

                    //添加 推广人的冻结资金

                    //update by cai 20180827 --start
                    //增加第一级推广人的冻结资金
                    $first_spread_id = $parent_id; //上一级的id
                    $this->updateFrozenAmount($client_id,$first_spread_id,1);

                    //增加第二级推广人的冻结资金
                    $second_spread = \DB::table('client_link_treepaths')->where([ //上两极的id
                        ['path_end_client_id', '=', $client_id],
                        ['dist', '=', '2'],
                    ])->first();
                    if($second_spread){
                        $second_spread_id = $second_spread->path_begin_client_id;
                        $this->updateFrozenAmount($client_id,$second_spread_id,2);
                    }
                    //--end

                }else{
                    $this->insertSelfNode($client_id);
                }
            }
        }catch (Exception $e){
            return response_format([],0,$e->getMessage(),$e->getCode());
        }
    }

    //插入自身的一条记录
    public function insertSelfNode($client_id){
        $node['path_begin_client_id'] = $client_id;
        $node['dist'] = 0;
        $node['is_leaf'] = 1;
        $node['path_end_client_id'] = $client_id;
        $node['created_at'] = Carbon::now();
        $node['updated_at'] = Carbon::now();
        \DB::table('client_link_treepaths')->insert($node);
    }


    public function checkBind($client_id)
    {
        $count = \DB::table('client_link_mapping')->where('child_client_id',$client_id)->count();
        if ($count){
            return true;
        }else{
            return false;
        }
    }
    //add by cai 20180904 --start
    public function checkShare($client_id){
        $has_bind = $this->checkBind($client_id);
        $count = Order::where(['client_id'=>$client_id,'order_status'=>4])->count();
        if($has_bind || $count){
            return true;
        }else{
            return false;
        }
    }

    public function checkFirstBuy($client_id){
        $count = Order::whereRaw('client_id = ? AND ( order_status = 0 OR ( pay_date IS NOT NULL AND return_date IS NULL))',[$client_id])
            ->count();
        if ($count){
            return false;
        }else{
            return true;
        }
    }
    //--end
    private function updateFrozenAmount($client_id, $parent_id, $spread_flag)
    {
        //update by cai 20180827 --start
        $spread_amount = $spread_flag == 1 ?  \SystemConfig::$first_spread_amount : \SystemConfig::$second_spread_amount;
        $record['client_id'] = $parent_id;
        $record['child_id'] = $client_id;
        $record['amount'] = $spread_amount;
        $record['type'] = 2;
        $client = Client::find($parent_id);
        $record['memo'] = $client->nick_name."增加冻结金额".$record['amount']."元";
        $record['spread_flag'] = $spread_flag;
        $record['status'] = 1;
        $record['updated_at'] = Carbon::now();
        $record['created_at'] = Carbon::now();
        //--end
        $id = \DB::table('client_amount_flow')->insertGetId($record);//更新资金流水记录表

        //更新用户资金表冻结金额
        $client_amount = ClientAmount::where('client_id',$parent_id)->first();
        if($client_amount){
            $amount['freezing_amount'] = $client_amount->freezing_amount + $record['amount'];
            $amount['amount'] = $client_amount->amount + $record['amount'] ;
            $res = $client_amount->update($amount);
        }else{
            $amount['client_id'] = $record['client_id'];
            $amount['amount'] = $record['amount'];
            $amount['freezing_amount'] = $record['amount'];
            $res = ClientAmount::create($amount)->uid;
        }

        if ($id > 0 && $res > 0){
            \Log::info($parent_id."冻结金额增加成功，金额为：".$record['amount']);
        }
    }
}