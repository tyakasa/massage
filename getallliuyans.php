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




$sql3 = "SELECT * FROM comments ";

$pant=mysqli_query($conn,$sql3);
$list=array();
while($rows =mysqli_fetch_assoc($pant))
{
    $list[]=[
        "mid"=>$rows["id"],
        "uid"=>$rows["write_id"],
        "write_name"=>$rows["write_name"],
        "content"=>$rows["content"],
        "write_time"=>$rows["write_time"]
    ];
}
$result = [
    'errcode' => 0,
    'errmsg' => '登陆成功',
    'data' => [
        "list"=>$list
]
    
];

echo json_encode($result);

  
mysqli_close($conn);
?>