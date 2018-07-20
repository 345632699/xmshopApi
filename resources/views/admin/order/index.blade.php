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
                                <th>下单日期</th>
                                <th>订单编号</th>
                                <th>订单类型</th>
                                <th>商品名称</th>
                                <th>数量</th>
                                <th>订单总价</th>
                                <th>昵称</th>
                                <th>发票信息</th>
                                <th>订单状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orderList as $key=>$item)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>
                                        <a href="{{ route('order.show',$item->uid) }}">
                                            {{ $item->order_number }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $item->order_type > 0 ? "货到付款" : "预付款" }}
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->quantity * $item->unit_price  }}</td>
                                    <td>
                                        {{ $item->nick_name }}
                                    </td>
                                    <td>
                                        {{ 'N' == $item->open_invoice_flag ? '无' : "有" }}
                                    </td>
                                    <td>
                                        {{ $item->order_status_name }}
                                    </td>
                                    <td>
                                        <a href="{{ route('order.eidt-delivery',$item->uid) }}" class="btn btn-default">发货</a>
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
