<?php
/**
 * Created by PhpStorm.
 * User: xu
 * Date: 2018/7/2
 * Time: 16:47
 */

namespace App\Repositories\Good;


interface GoodRepositoryInterface {
    /**
     * @param $good_id
     * @return mixed
     * 获取商品详情
     */
    public function getGood($good_id);
}