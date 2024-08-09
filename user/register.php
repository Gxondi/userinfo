<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>用户注册</title>
    <link rel="stylesheet" href="/public/css/login.css">
    <!-- <?php include "./common/head.php" ?> -->
</head>
<body>
    <!-- <?php include "./common/header.php" ?> -->
    <div class="container">
        <h1 style="margin-bottom: 10px;text-align: center">用户注册</h1>

        <form action="" id="submit_form" onsubmit="submitForm(event)">
            <ul>
                <li class="line">
                    <input type="text" name="username" placeholder="用户名" required autocomplete="nusername">
                </li>
                <li class="line">
                    <input type="password" name="password" placeholder="密码" required autocomplete="password">
                </li>
                <li class="line">
                    <input type="password" name="confirm_password" placeholder="确认密码" required autocomplete="new-password">
                </li>
                <li class="line">
                    <button type="submit" name="提交登录">注册</button>
                </li>
                <li class="line">
                    <p>已有账号？ <a href="./login.php">请登录</a></p>
                </li>
            </ul>
        </form>
    </div>
    <?php include "./common/footer.php" ?>
    <script>
        function isEmpyt(value) {
            return value.trim() === "";
        }

        function strlenMin(value) {
            return value.length < 6;
        }

        function submitForm(event) {
            event.preventDefault();
            var form = document.getElementById("submit_form");
            var formData = new FormData(form);
            var datas = formData.entries();
            var pass = "";
            var re_pass = "";

            for (const data of datas) {
                if (isEmpyt(data[1])) {
                    alert(data[1] + "不能为空");
                    return;
                }
                if (data[0].indexOf("password") >= 0) {
                    if (data[0] == 'password') {
                        pass = data[1];
                    } else {
                        re_pass = data[1];
                    }
                    if (strlenMin(data[1])) {
                        alert(data[0] + "长度不能小于6");
                        return;
                    }
                }
            }
            if (pass !== re_pass) {
                alert("两次密码不一致");
                return;
            }

            var url = "/api/register.php";
            fetch(url, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.code === 200) {
                    alert(data.message);
                    window.location.href = "/user/login.php";
                } else {
                    alert(data.message);
                }
            }).catch(error => {
                console.log('error', error);
            });
        }
    </script>
</body>
</html>