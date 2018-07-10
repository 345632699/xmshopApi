<?php
/**
 * Created by PhpStorm.
 * User: xu
 * Date: 2018/7/2
 * Time: 16:47
 */

namespace App\Repositories\Client;


interface ClientRepositoryInterface {
    public function selectAll();

    public function find($id);

    public function getUserByOpenId();

    /**
     * @param $client_id 当前用户ID
     * @param $parent_id  推广人用户ID
     * @return mixed
     *
     * 更新叶子节点信息
     */
    public function updateTreeNode($client_id,$parent_id);
}