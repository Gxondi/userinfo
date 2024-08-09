<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

if (!isset($_SESSION['user'])) {
    die("请先登录");
}

$user = $_SESSION['user'];
$title = $_POST['title'] ?? '';
$body = $_POST['body'] ?? '';

if (empty($title) || empty($body)) {
    die("标题和内容不能为空");
}

// 连接数据库
$servername = "localhost";
$username = "admin";
$password = "1026184114";
$dbname = "userinfo";
$port = 3307;
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// 检查连接
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// 获取当前时间
$createtime = date('Y-m-d H:i:s');
$updatetime = $createtime;

// 插入文章数据
$stmt = $conn->prepare("INSERT INTO article (title, body, createtime, updatetime) VALUES (?, ?, ?, ?)");
if (!$stmt) {
    die("失败: " . $conn->error);
}
$stmt->bind_param("ssss", $title, $body, $createtime, $updatetime);

if ($stmt->execute()) {
    // 获取插入的文章id
    $article_id = $stmt->insert_id;

    // 插入到中间表 article_user
    $stmt2 = $conn->prepare("INSERT INTO article_user (article_id, user_id, createtime, updatetime) VALUES (?, ?, ?, ?)");
    if (!$stmt2) {
        die("失败: " . $conn->error);
    }
    $stmt2->bind_param("iiss", $article_id, $user['id'], $createtime, $updatetime);

    if ($stmt2->execute()) {
        echo "文章发布成功";
        
    } else {
        echo "文章发布成功，但关联到用户失败: " . $stmt2->error;
    }

    $stmt2->close();
} else {
    echo "文章发布失败: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>