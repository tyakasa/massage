<?php
session_start();
header('Content-Type: application/json');

$conn = mysqli_connect("localhost","root","","message");

if(! $conn ){
    $result = [
        'errcode' =>19,
        'errmsg' =>'数据库连接失败',
        'data'=>''
    ];
    echo json_encode($result);
   
    die();   
}
$flag = 1;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $mid=$_POST["mid"];
    if($mid==""){
        $result = [
            'errcode' =>13,
            'errmsg' =>'参数不全',
            'data' =>''
        ];

        echo json_encode($result);
        die();
    }

    $delete = "DELETE FROM comments WHERE id =?";
    $flag =0;
}else{
    $delete = "DELETE FROM comments ";
}



$pre_sql = mysqli_prepare($conn,$delete);
if ($flag==0) {
$pre_sql->bind_param("i",$mid);
}
if($pre_sql->execute()){
    $result = [
        'errcode' =>0,
        'errmsg' =>'删除成功',
        'data'=>[]
    ];
    echo json_encode($result);
    die();
}else{
    $result = [
        'errcode' =>2,
        'errmsg' =>'删除失败',
        'data' =>''
    ];
    echo json_encode($result);
    die();
}
$pre_sql->free_result();
$pre_sql->close();


mysqli_close($conn);
?>