<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>文章列表</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        .table-container {
            max-height: 400px; 
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <h1>文章列表</h1>
    <div class="table-container">
        <table id="articleTable">
            <thead>
                <tr>
                    <th>标题</th>
                    <th>内容</th>
                </tr>
            </thead>
            <tbody>
                <!-- 文章数据 -->
            </tbody>
        </table>
    </div>

    <script>
        // 操作列表
        document.addEventListener('DOMContentLoaded', function() {
            // 请求文章数据
            fetch('/api/getArticles.php')
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                    } else {
                        // 渲染文章列表
                        const tableBody = document.querySelector('#articleTable tbody');
                        data.forEach(article => {
                            const row = document.createElement('tr');
                            const titleEl = document.createElement('td');
                            const bodyEl = document.createElement('td');
                            titleEl.textContent = article.title;
                            bodyEl.textContent = article.body;
                            row.appendChild(titleEl);
                            row.appendChild(bodyEl);
                            tableBody.appendChild(row);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching articles:', error);
                });
        });
    </script>
</body>
</html>