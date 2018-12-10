<?php
session_start();
header('Content-Type: application/json');

$conn = mysqli_connect("localhost","root","","message");
mysqli_set_charset($conn, "utf8");
if(! $conn ){
    $result = [
        'errcode' =>19,
        'errmsg' =>'数据库连接失败',
        'data'=>''
    ];
    echo json_encode($result);
   
    die();   
}
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

$update = "UPDATE comments set `content` = ?, `write_time` = ?  WHERE id =?";


$content=$_POST["content"];
$write_time = $_POST["write_time"];

if (isset($content)){  
    $content = htmlspecialchars($content); 
}  

$pre_sql = mysqli_prepare($conn,$update);
$pre_sql->bind_param("ssi",$content,$write_time,$mid);

if($pre_sql->execute()){
    $result = [
        'errcode' =>0,
        'errmsg' =>'更新成功',
        'data'=>["content"=> $content]
    ];
    echo json_encode($result);
    die();
}else{
    $result = [
        'errcode' =>2,
        'errmsg' =>'更新失败',
        'data' =>''
    ];
    echo json_encode($result);
    die();
}


  
$pre_sql->free_result();
$pre_sql->close();


mysqli_close($conn);
?>