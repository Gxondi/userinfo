<?php
        session_start();
        $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
    ?>
    <header>
        <div class="container-full">
            <div class="stiename">
                我的网站
            </div>
            <?php
            if (isset($user)) {
                echo "欢迎, " . htmlspecialchars($user['username']) . "!";
            } else {
                echo '<a href="/user/login.php" class="login-button">登录</a>';
            }
            ?>
            <div class="userinfo">
                <?php
                if (isset($user)) {
                    echo htmlspecialchars($user['username']);
                } else {
                    echo "测试用户";
                }
                ?>
                <?php if (isset($user)): ?>
                    <a href="/user/logout.php" class="logout-button">退出</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
