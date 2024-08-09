<?php
session_start();

require_once '../func/Db.class.php';

$username = $_POST['username'];
$password = $_POST['password'];
$password = md5($password);

$db = new Db();
$user = $db->table('users')->where([['username','=', "$username"]])->select();

$message = "";

if ($user) {
    $user = $user[0]; // 提取第一个用户信息
    if ($user['password'] !== $password) {
        $message = "用户名或密码错误";
        $responseData = [
            "code" => 400,
            "message" => $message,
            "data" => []
        ];
        echo json_encode($responseData, JSON_UNESCAPED_UNICODE);
        exit;
    }
    // 将用户数据存储在 session 中
    $_SESSION['user'] = $user;
    $responseData = [
        "code" => 200,
        "message" => "登录成功",
        "data" => $user
    ];
} else {
    $message = "用户名或密码错误";
    $responseData = [
        "code" => 400,
        "message" => $message,
        "data" => []
    ];
}

// 返回 JSON 响应
echo json_encode($responseData, JSON_UNESCAPED_UNICODE);
?>