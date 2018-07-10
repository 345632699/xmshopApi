<?php
/**
 * Created by PhpStorm.
 * User: xu
 * Date: 2018/7/2
 * Time: 16:49
 */

namespace App\Repositories\Client;
use JWTAuth;
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
        \DB::table('client_link_treepaths')->where('path_end_')

    }
}