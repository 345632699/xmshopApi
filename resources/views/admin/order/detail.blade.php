@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">订单详情 <span style="color: red">{{ $order->order_status_name }}</span></div>
                    <div class="panel-body">
                        <div>
                            <p>
                                <label>订单号:</label> <span>{{ $order->order_number }}}</span>
                            </p>
                            <p>
                                <label>用户名:</label> <span>{{ $order->nick_name }}</span>
                            </p>
                            <p>
                                <label>收货地址:</label> <span>{{ $delivery->address }}</span>
                            </p>
                            <p>
                                <label>订单备注:</label> <span>{{ $order->buyer_msg }}</span>
                            </p>
                        </div>
                        <hr>
                        <div>
                            <p>开票资料：</p>
                            @if(isset($invoice->invoice_type))
                                <p>
                                    @if($invoice->invoice_type > 0)
                                        <label>公司:</label>
                                    @else
                                        <label>个人:</label>
                                    @endif
                                    <span>{{ $invoice->title }}</span>
                                </p>
                                <p>
                                    <label>税号:</label> <span>{{ $invoice->tax_code }}</span>
                                </p>
                                <p>
                                    <label>金额:</label> <span>{{ $invoice->amount }}</span>
                                </p>
                                <p>
                                    <label>手机号:</label> <span>{{ $invoice->phone_num }}</span>
                                </p>
                            @else
                                <p>无</p>
                            @endif

                        </div>
                        <hr>
                        <div>
                            <p>商品信息：</p>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <th>商品名称</th>
                                    <th>型号</th>
                                    <th>颜色</th>
                                    <th>数量</th>
                                    <th>单价</th>
                                    <th>总价</th>
                                    <th>状态</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>{{ $good->name }}</th>
                                        <th>{{ $order->size }}</th>
                                        <th>{{ $order->color }}</th>
                                        <th>{{ $order->quantity }}</th>
                                        <th>{{ $order->unit_price }}</th>
                                        <th>{{ $order->unit_price * $order->quantity }}</th>
                                        <th>{{ $order->order_status_name }}</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <div>
                            <p>快递信息</p>
                            <p>
                                <label>快递单号:</label> <span>{{ $delivery->delivery_number }}</span>
                            </p>
                            <p>
                                <label>机器人序列号:</label> <span>{{ $product_ids }}</span>
                            </p>
                        </div>
                        <div>
                            <p>
                                更改订单状态：
                            </p>
                            <form action="{{ route('order.update-status') }}" method="post" class="text-center">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="order_id" value="{{ $order->uid }}">
                                <span class="col-md-2">
                                    <input class="radio-inline" name="order_status" type="radio" value="0">
                                    <label class="control-label" for="order_status">未支付</label>
                                </span>
                                <span class="col-md-2">
                                    <input class="radio-inline" name="order_status" type="radio" value="1">
                                    <label class="control-label" for="order_status">已支付</label>
                                </span>
                                <span class="col-md-2">
                                    <input class="radio-inline" name="order_status" type="radio" value="2">
                                    <label class="control-label" for="order_status">待发货</label>
                                </span>
                                <span class="col-md-2">
                                    <input class="radio-inline" name="order_status" type="radio" value="3">
                                    <label class="control-label" for="order_status">已发货</label>
                                </span>
                                <span class="col-md-2">
                                    <input class="radio-inline" name="order_status" type="radio" value="4">
                                    <label class="control-label" for="order_status">已完成</label>
                                </span>
                                <span class="col-md-2">
                                    <input class="radio-inline" name="order_status" type="radio" value="5">
                                    <label class="control-label" for="order_status">异常</label>
                                </span>
                                <span class="col-md-2">
                                    <input class="radio-inline" name="order_status" type="radio" value="6">
                                    <label class="control-label" for="order_status">申请退货</label>
                                </span>
                                <span class="col-md-2">
                                    <input class="radio-inline" name="order_status" type="radio" value="7">
                                    <label class="control-label" for="order_status">确认退货</label>
                                </span>
                                <span class="col-md-2">
                                    <input class="radio-inline" name="order_status" type="radio" value="8">
                                    <label class="control-label" for="order_status">已退货</label>
                                </span>
                                <span class="col-md-2">
                                    <input class="radio-inline" name="order_status" type="radio" value="9">
                                    <label class="control-label" for="order_status">已取消</label>
                                </span>
                                <hr>
                                <p class="col-sm-12">
                                    <input style="width: 30%" type="submit" class="btn btn-primary form-control" value="确认更改">
                                </p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
@endsection
