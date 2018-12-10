<?php
session_start();
header('Content-Type: application/json');

$conn =mysqli_connect("localhost","root","","message");

if(! $conn ){
    $result = [
        'errcode' =>19,
        'errmsg' =>'数据库连接失败',
        'data'=>''
    ];
    echo json_encode($result);
    die();   
}
mysqli_set_charset($conn, "utf8");

$content=$_POST["content"];
$named=$_POST["named"];
$time=$_POST["time"];

if($content=="" || $named=="" || $time==""){
    $result = [
        'errcode' =>13,
        'errmsg' =>'参数不全',
        'data' =>''
    ];

    echo json_encode($result);
    die();
}

if (isset($content)){  
    $content = htmlspecialchars($content); 
}  


$uid = $_SESSION['id'];
$sqli2="INSERT INTO comments(write_name,write_id,content,write_time) VALUES (?,?,?,?)";



$pre_sql = mysqli_prepare($conn,$sqli2);
$pre_sql->bind_param("siss",$named,$uid,$content,$time);
	if($pre_sql->execute()){

        $pre_sql->free_result();
        $pre_sql->close();
        $pant= mysqli_query($conn,"select last_insert_id() as mid");
        $rent = mysqli_fetch_assoc($pant);
        $result = [
            'errcode' =>0,
            'errmsg' =>'留言成功',
            'data'=>['mid'=>$rent["mid"],'uid'=>$uid,'content'=>$content]
        ];
        echo json_encode($result);
        die();
	}else{
        $result = [
            'errcode' =>2,
            'errmsg' =>'留言失败',
            'data' =>''
        ];
        echo json_encode($result);
        die();
    }
    




  
mysqli_close($conn);
?>