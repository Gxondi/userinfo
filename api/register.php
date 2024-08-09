<?php
 require_once '../func/Db.class.php'; 


function isEmpty($value) {
    return $value === null || empty($value);
}

function strlenMin($value) {
    return strlen($value) < 6;
}

$message = "";
foreach (["username", "password"] as $key => $value) {
    if (!isset($_POST[$value])) {
        $message = $value . "不能为空";
        break;
    }
    if (isEmpty($_POST[$value])) {
        $message = $value . "不能为空";
        break;
    }
    if ($value === 'password' && strlenMin($_POST[$value])) {
        $message = $value . "长度不能小于6";
        break;
    }
}

$responseData = [
    "code" => 400,
    "message" => $message,
    "data" => []
];

if ($message === "") {
    try {
        // 将数据添加到数据库
        $username = $_POST['username'];
        $password = $_POST['password'];
        $password = md5($password);
        $db = new Db();
        $result = $db->table('users')->insert(['username' => $username, 'password' => $password, 'createtime' => time(), 'updatetime' => time()]);
        
        if ($result) {
            $responseData["code"] = 200;
            $responseData["message"] = "注册成功";
        } else {
            throw new Exception("数据插入失败");
        }
    } catch (Exception $e) {
        $responseData["code"] = 500;
        $responseData["message"] = "服务器错误：" . $e->getMessage();
    }
}

echo json_encode($responseData, JSON_UNESCAPED_UNICODE);
?>