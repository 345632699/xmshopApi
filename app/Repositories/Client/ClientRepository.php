<?php
/**
 * Created by PhpStorm.
 * User: xu
 * Date: 2018/7/2
 * Time: 16:49
 */

namespace App\Repositories\Client;
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
}