@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">更新发货信息</div>
                    <div class="panel-body">
                        <form action="{{ route('order.update-delivery') }}" method="post" class="form-group">
                            <div class="col-md-12">
                                <label for="name" class="control-label col-md-3">快递名称：</label>
                                <input type="text" name="name" class="form-control col-md-8">
                            </div>
                            <div class="col-md-12">
                                <label for="name" class="control-label col-md-3">快递单号：</label>
                                <input type="text" name="delivery_number" class="form-control col-md-8">
                            </div>
                            <div class="col-md-12">
                                <label for="name" class="control-label col-md-3">机器人序列号：</label>
                                <textarea rows="3" placeholder="请用,号隔开" name="product_ids" class="form-control col-md-8"></textarea>
                            </div>
                            <input type="hidden" name="order_id" value="{{ $order_id }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="col-md-12 text-center">
                                <hr>
                                <input type="submit" value="确认提交" class="btn btn-primary col-sm-4 col-sm-offset-4">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
@endsection
