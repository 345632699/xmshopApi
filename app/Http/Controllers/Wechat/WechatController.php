<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WechatController extends Controller
{
    public function mini(Request $request){
        $code = $request->code;
        if (!isset($code)){
            return response('參數錯誤');
        }
        $app = app('wechat.mini_program');
        $app->auth->session($code);
    }
}
