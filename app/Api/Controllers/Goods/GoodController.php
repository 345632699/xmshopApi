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

    public function index(Request $request) {
        $good_id = $request->input('good_id',1);
        $goods = $this->goods->getGood($good_id);
        return $goods;
    }
}
