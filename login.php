<?php
session_start();
header('Content-Type:application/json');
$conn = mysqli_connect("localhost","root","","message");
mysqli_query($conn,"set names utf8");
$timezone="Asia/BeiJing";
if(! $conn ){
    $result = [
        'errcode' =>6,
        'errmsg' =>'数据库连接错误',
        'data' =>''
    ];
    echo json_encode($result);
    die();
}

$username=$_POST["username"];
$password=$_POST["password"];
$result = [];
if(! $username || ! $password){
    $result = [
        'errcode' =>16,
        'errmsg' =>'请输入账户或密码',
        'data' =>''
    ];
    echo json_encode($result);
    die();
}

if (isset($username)){  
    $username = htmlspecialchars($username);
} 


$sql4="SELECT `username` from users WHERE `username`=?";
$pre_sql = mysqli_prepare($conn,$sql4);
$pre_sql->bind_param("s",$username);

$pre_sql->bind_result($my_username);
	if($pre_sql->execute()){
		if($pre_sql->fetch()){

		}else{
            $result = [
                'errcode' =>1,
                'errmsg' =>'用户名不存在',
                'data' =>''
            ];
            echo json_encode($result);
            die();
        }	

	}else{
        $result = [
            'errcode' =>1,
            'errmsg' =>'预处理查询失败',
            'data' =>''
        ];
        echo json_encode($result);
        die();
	}

   


$pre_sql->free_result();
$pre_sql->close();

date_default_timezone_set("PRC");
$time = date("Y-m-d H:i:s");

$sql1 = "SELECT `times`, `last_login_time` FROM users WHERE `username`=? and `password`=?";
$sql2="UPDATE users set `times` = `times`+1, `last_login_time` = ?  WHERE `username`=?";
$pre_sql = mysqli_prepare($conn,$sql1);
$pre_sql->bind_param("ss",$username,$password);
$pre_sql->bind_result($times,$last_login_time);
	if($pre_sql->execute()){
		if($pre_sql->fetch()){
            $username=$_POST["username"];
            $password=$_POST["password"];
            if (isset($username)){  
                $username = htmlspecialchars($username);
            } 
            $pre_sql->free_result();
            $pre_sql->close();

            $pre_sql = mysqli_prepare($conn,$sql2);
            $pre_sql->bind_param("ss",$time,$username);
            $pre_sql->execute();
            $pre_sql->free_result();
            $pre_sql->close();


            $sql3 = "SELECT id,`times`, `last_login_time` FROM users WHERE `username`=?";
            $pre_sql = mysqli_prepare($conn,$sql3);
        $pre_sql->bind_param("s",$username);
        $pre_sql->bind_result($uid,$times,$last_login_time);
	    if($pre_sql->execute()){
		if($pre_sql->fetch()){
            $result = [
                'errcode' => 0,
                'errmsg' => '登陆成功',
                'data' => [
                    "number_of_times" =>  $times,
                    "last_login_time" =>  $last_login_time? $last_login_time: $time
            ],
                
            ];
            $pre_sql->free_result();
            $pre_sql->close();
        
            $_SESSION['username'] = $username;
            $_SESSION['id'] = $uid;
            echo json_encode($result);
            die();
        }}else{
            $result = [
                'errcode' =>1,
                'errmsg' =>'预处理查询失败',
                'data' =>''
            ];
            echo json_encode($result);
            die();
        }

         

		}else{
            $result = [
                'errcode' => 233,
                'errmsg' => '密码错误',
                'data' => ''
            ];
            echo json_encode($result);
            die();
        }	

	}else{
        $result = [
            'errcode' =>1,
            'errmsg' =>'预处理查询失败',
            'data' =>''
        ];
        echo json_encode($result);
        die();
    }
    




$pre_sql->free_result();
$pre_sql->close();

mysqli_close($conn);
?>