<?php
header('Content-Type: application/json');

$conn = mysqli_connect("localhost","root","","message");
mysqli_query($conn,"set names utf8");
if(! $conn ){
    $result = [
        'errcode' =>19,
        'errmsg' =>'数据库连接失败',
        'data'=>''
    ];
    echo json_encode($result);
    die();   
}

$usernames=$_POST["username"];
$password=$_POST["password"];
$checkpwd=$_POST["checkpwd"];

if (isset($usernames)){  
    $usernames = htmlspecialchars($usernames);
}  
//将字符内容转化为html实体
if($usernames=="" || $password=="" || $checkpwd==""){
    $result = [
        'errcode' =>13,
        'errmsg' =>'请输入账户或密码或确认密码',
        'data' =>''
    ];

    echo json_encode($result);
    die();
}
//创建准备查询语句
$sqli1 ="SELECT username FROM users WHERE username=?";
$pre_sql = mysqli_prepare($conn,$sqli1);
$pre_sql->bind_param("s",$usernames);
//bind_param()绑定参数，"s"为字符串类型,记得"i"为int类型
$pre_sql->bind_result($my_username);
	if($pre_sql->execute()){
		if($pre_sql->fetch()){
            $result = [
                'errcode' =>15,
                'errmsg' =>'用户名已存在',
                'data'=>''
            ];
            echo json_encode($result);
            die();   
		}else{
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

//
$pre_sql->free_result();
$pre_sql->close();

      
if($_POST['password'] != $_POST['checkpwd']){
    $result = [
        'errcode' =>8,
        'errmsg' =>'两次输入密码不一致',
        'data' =>''
    ];
    echo json_encode($result);
    die();
}

$sqli2="INSERT INTO users(`username`,`password`) VALUES (?,?)";

$pre_sql = mysqli_prepare($conn,$sqli2);
$pre_sql->bind_param("ss",$usernames,$password);
	if($pre_sql->execute()){
        $result = [
            'errcode' =>0,
            'errmsg' =>'注册成功',
            'data'=>["注册成功"]
        ];
        echo json_encode($result);
        die();
	}else{
        $result = [
            'errcode' =>2,
            'errmsg' =>'注册失败',
            'data' =>''
        ];
        echo json_encode($result);
        die();
    }
    
$pre_sql->free_result();
$pre_sql->close();

mysqli_close($conn);
?>