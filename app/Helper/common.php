<?php

function response_format($data,$status=1,$msg='success',$code = 200){
    $res = [];
    $res['data'] = $data;
    $res['status'] = $status;
    $res['msg'] = $msg;
//    return json_encode($res,JSON_UNESCAPED_UNICODE);
    return response()->json(['response' => $res], $code,[],JSON_UNESCAPED_UNICODE);
}