<div class="top">
    <form id="articleForm">
        <ul>
            <li class="title">
                <input type="text" name="title" id="title" placeholder="文章标题" required autocomplete="title">
            </li>

            <li class="body">
                <textarea name="body" id="body" cols="30" rows="10" required></textarea>
            </li>
            <li class="buttons">
                <button type="button" class="btn" id="submitBtn">发布</button>
                <button type="reset" class="btn">清空</button>
            </li>
        </ul>
    </form>
</div>

    <script>
     //addEventListener 事件监听
        document.getElementById('submitBtn').addEventListener('click', function() {
        const title = document.getElementById('title').value;
        const body = document.getElementById('body').value;

        fetch('../api/submit_article.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            //encodeURIComponent 进行编码
            body: `title=${encodeURIComponent(title)}&body=${encodeURIComponent(body)}`
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
    </script>