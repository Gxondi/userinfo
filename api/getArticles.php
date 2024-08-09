<?php
session_start(); 
header('Content-Type: application/json');

// 是否登录
if (!isset($_SESSION['user'])) {
    echo json_encode(["error" => "用户未登录"]);
    exit;
}

$user = $_SESSION['user']; // 获取当前登录用户的信息
$user_id = $user['id']; // 从用户信息中获取用户 ID

$servername = "localhost";
$username = "admin";
$password = "1026184114";
$dbname = "userinfo";
$port = 3307;
$conn = new mysqli($servername, $username, $password, $dbname, $port);

if ($conn->connect_error) {
    die(json_encode(["error" => "连接失败: " . $conn->connect_error]));
}

// 用户的文章id
$sql = "SELECT article_id FROM article_user WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$article_ids = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $article_ids[] = $row['article_id'];
    }
} else {
    echo json_encode(["error" => "没有文章"]);
    exit;
}
$stmt->close();

// 获取文章数据
if (!empty($article_ids)) {
    // 将文章 ID 转换为逗号分隔的字符串
    $article_ids_str = implode(',', array_fill(0, count($article_ids), '?'));
    
    // 查询
    $sql = "SELECT title, body FROM article WHERE id IN ($article_ids_str)";
    $stmt = $conn->prepare($sql);
    
    // 绑定参数
    $stmt->bind_param(str_repeat('i', count($article_ids)), ...$article_ids);
    $stmt->execute();
    $result = $stmt->get_result();

    $articles = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $articles[] = $row;
        }
    } else {
        echo json_encode(["error" => "没有文章"]);
        exit;
    }
    $stmt->close();
    $conn->close();

    echo json_encode($articles);
} else {
    echo json_encode(["error" => "没有文章"]);
}
?>