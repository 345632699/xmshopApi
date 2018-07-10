<?php

namespace App\Api\Controllers\Home;

use App\Api\Controllers\BaseController;
use App\Model\Good;
use Illuminate\Http\Request;
use Mockery\Exception;


class HomeController extends BaseController
{
    public function index(Request $request) {
        $good_id = $request->input('good_id',1);
    }
}
