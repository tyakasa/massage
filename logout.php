<?php 
   session_start();
 
try
 {
    unset($_SESSION['uid']);
    unset($_SESSION['username']);
    session_destroy();
    $result = [
        'errcode' =>0,
        'errmsg' =>'注销成功',
        'data' =>''
    ];
    echo json_encode($result);
 }
catch(Exception $e)
 {
    $result = [
        'errcode' =>1,
        'errmsg' =>'注销失败',
        'data' =>''
    ];
    echo json_encode($result);
 }
 
   
?>

