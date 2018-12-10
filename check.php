<?php
session_start();
header('Content-Type:application/json');

if(!isset($_SESSION['id'])){
    $result = [
        'errcode' =>1,
        'errmsg' =>'用户未登录',
        'data' =>''
    ];
    echo json_encode($result);
}else{
    $result = [
        'errcode' =>0,
        'errmsg' =>'用户已登录',
        'data' =>[
            'id'=>$_SESSION['id'],
            'username'=>$_SESSION['username']
        ]
    ];
    echo json_encode($result);
}

?>