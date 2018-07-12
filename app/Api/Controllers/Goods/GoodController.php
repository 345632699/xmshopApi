<?php

namespace App\Api\Controllers\Goods;

use App\Api\Controllers\BaseController;
use App\Model\Good;
use App\Repositories\Good\GoodRepository;
use Illuminate\Http\Request;
use Mockery\Exception;

class GoodController extends BaseController
{
    public function __construct(GoodRepository $goods)
    {
        $this->goods = $goods;
    }

    /**
     * @api {get} /good/{good_id} 获取商品详情
     * @apiName 获取商品详情
     * @apiGroup Good
     *
     * @apiParam {int} good_id 商品ID
     *
     * @apiSuccess {Array} data 返回的数据结构体
     * @apiSuccess {Number} status  1 执行成功 0 为执行失败
     * @apiSuccess {string} msg 执行信息提示
     *
     *
     */
    public function index($good_id = 1) {
        $goods = $this->goods->getGood($good_id);
        return $goods;
    }
}
