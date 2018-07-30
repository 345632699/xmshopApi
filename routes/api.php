<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {
    $api->group(['namespace' => 'App\Api\Controllers','middleware' => ['client.change']], function ($api) {
        $api->post('user/login', 'AuthController@authenticate');  //登录授权
        $api->post('user/register', 'AuthController@register');
        $api->group(['middleware' => 'jwt.auth'], function ($api) {

            //路径为 /api/tests
            $api->get('tests', 'TestsController@index');
            //请求方式：
            //http://localhost:8000/api/tests?token=xxxxxx  (从登陆或注册那里获取,目前只能用get)
            $api->get('tests/{id}', 'TestsController@show');
            $api->post('user/me', 'AuthController@AuthenticatedUser'); //根据
        });

        //商品路由
        $api->get('good','Goods\GoodController@index');
        //支付回调
        $api->any('pay/notify','Pay\PayController@payNotify');

        //收货地址
        $api->group(['middleware' => ['jwt.auth','scope']], function ($api) {
            $api->get('address/list','Address\AddressController@index');
            $api->get('address/get','Address\AddressController@get');
            $api->post('address/create','Address\AddressController@create');
            $api->post('address/edit','Address\AddressController@edit');
            $api->post('address/delete','Address\AddressController@delete');

            $api->get('order/list','Order\OrderController@getOrderList');
            $api->get('order/get','Order\OrderController@get');
            $api->post('order/create','Order\OrderController@create');
            $api->post('order/confirm','Order\OrderController@confirmReceipt');
            $api->post('order/cancel','Order\OrderController@cancelOrder');

        });

        //用户中心
        $api->group(['middleware' => ['jwt.auth','scope']], function ($api) {
            $api->get('client','Client\ClientController@index');
            $api->get('client/check','Client\ClientController@checkBind');

            $api->post('pay/withdraw_list','Pay\PayController@getWithDrawRecordList');
        });
    });
});