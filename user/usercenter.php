<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>用户中心</title>
    <link rel="stylesheet" href="/public/css/login.css">
    <?php include "./common/head.php" ?>
</head>
<style>
header {
    margin-bottom: 20px; /* 确保header和top之间有间隔 */
}

.top {
    padding: 20px;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 5px;
    width: 50%;
    max-width: 600px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin: 0 auto; 
}

</style>
<body>
    <?php include "./common/header.php" ?>
    <div class="main">
        <!-- 顶部区域 -->
        <div class="top">
            <?php include "./common/top.php" ?>
        </div>
        <!-- 下部区域 -->
         <div class="article">
            <?php include "./common/articleList.php" ?>
         </div>
   </div>
    <?php include "./common/footer.php" ?>
</body>

</html>