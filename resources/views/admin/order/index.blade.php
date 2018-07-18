@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">订单列表</div>

                    <div class="panel-body">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>序号</th>
                                <th>姓名</th>
                                <th>地址</th>
                                <th>电话</th>
                                <th>订单号</th>
                                <th>状态</th>
                                <th>物流信息</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orderList as $key=>$item)
                                <tr class=" @if($item->status == 5)
                                        active
                                        @elseif($item->status == 4)
                                        danger
                                        @elseif($item->status == 3)
                                        success
                                        @elseif($item->status == 2)
                                        active
                                        @elseif($item->status == 1)
                                        warning
                                        @else
                                        active
                                        @endif
                                        ">
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td style="text-align: left">{{ $item->address }}</td>
                                    <td>{{ $item->mobile }}</td>
                                    <td>{{ $item->pay_order_sn }}</td>
                                    <td>
                                        @if($item->status == 5)
                                            已签收
                                        @elseif($item->status == 4)
                                            已退货
                                        @elseif($item->status == 3)
                                            已发货
                                        @elseif($item->status == 2)
                                            已取消
                                        @elseif($item->status == 1)
                                            已支付
                                        @elseif($item->status == 6)
                                            退货中
                                        @else
                                            未支付
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->tracking != null)
                                            {{ $item->tracking }}
                                        @else
                                            <button class="btn btn-primary" id="{{ $item->id }}" onclick="add_tracking_btn(this)">添加物流</button>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->status == 6)
                                            <button class="btn btn-danger" id="{{ $item->id }}" onclick="refund_btn(this)">确认退货</button>
                                        @elseif($item->status == 1)
                                            <button class="btn btn-success" id="{{ $item->id }}" onclick="confirm_btn(this)">确认发货</button>
                                        @elseif($item->status == 6)
                                            <button class="btn btn-primary" id="{{ $item->id }}" onclick="add_tracking_btn(this)">更新物流</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="pull-right">{{ $orderList->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
@endsection
