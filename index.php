<!DOCTYPE html>
<html lang="zh-cn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>用户登录</title>
    <link rel="stylesheet" href="/public/css/login.css">
    <?php include "./common/head.php" ?>
</head>

<body>
    <?php include "./common/header.php" ?>
    <div class="container">
        <h1 style="margin-bottom: 10px;text-align: center">用户登录</h1>
        <form action="login.php" id="submit_form" method="post" onsubmit="submitForm(event)">
            <ul>
                <li class="line">
                    <input type="text" name="username" placeholder="用户名" required>
                </li>
                <li class="line">
                    <input type="password" name="password" placeholder="密码" required>
                </li>
                <li class="line">
                    <button type="submit" name="提交登录">登录</button>
                </li>
                <li class="line">
                    <p><a href="/user/register.php">新用户注册</a></p>
                </li>
            </ul>
        </form>
    </div>
    <?php include "./common/footer.php" ?>
    <!-- 登录script -->
    <script>
        function isEmpyt(value) {
            return value.trim() === "";
        }

        function submitForm(event) {
            event.preventDefault();
            var form = document.getElementById("submit_form");
            var formData = new FormData(form);
            var datas = formData.entries();

            for (const data of datas) {
                if (isEmpyt(data[1])) {
                    alert(data[0] + "不能为空");
                    return;
                }
            }


            var url = "/api/login.php";
            fetch(url, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.code === 200) {
                    alert(data.message);
                    // 将 data 存储在 localStorage 中
                    localStorage.setItem('userData', JSON.stringify(data.data));
                    window.location.href = "/user/usercenter.php";
                } else {
                    alert(data.message);
                }
            }).catch(error => {
                console.log('error', error);
                alert(error.getMessage);
            });
            
        }
    </script>
</body>

</html>