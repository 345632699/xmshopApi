<?php

namespace App\Api\Controllers\Pay;

use App\Api\Controllers\BaseController;
use App\Model\Client;
use EasyWeChat\Payment\Application;
use Illuminate\Http\Request;


class PayController extends BaseController
{
    public function index() {

    }

    public function create(){

    }

    public function payNotify() {
        $app = app('wechat.payment');
        $response = $app->handlePaidNotify(function ($message, $fail) {
            // 你的逻辑
            return true;
            // 或者错误消息
            $fail('Order not exists.');
        });

        $response->send(); // Laravel 里请使用：return $response;
    }

}
