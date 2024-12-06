<?php
require_once 'backend/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $content = trim($data['content'] ?? '');

    if (empty($content)) {
        echo json_encode(['success' => false, 'message' => 'Текст публикации не может быть пустым']);
        exit;
    }

    if (isset($_SESSION['user_id'])) {
        $query = $pdo->prepare('INSERT INTO posts (content, author_id) VALUES (:content, :author_id)');
        $success = $query->execute([
            ':content' => $content,
            ':author_id' => $_SESSION['user_id'],
        ]);

        if ($success) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Ошибка при добавлении публикации']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Неавторизованный пользователь']);
    }
    exit;
}

?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавление публикации</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background-color: #12263A;
            color: #F0E6D2;
            padding: 20px;
            border-radius: 8px;
            width: 50%;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .form-container textarea {
            width: 90%;
            height: 200px;
            margin-bottom: 20px;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: none;
            resize: none;
        }

        .form-container button {
            background-color: #D4AF89;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Добавление публикации</h2>
        <textarea id="post-content" placeholder="Текст публикации"></textarea>
        <br>
        <button onclick="submitPost()">Создать</button>
    </div>

    <script>
        function submitPost() {
            const content = document.getElementById('post-content').value;

            if (content.trim() === '') {
                alert('Введите текст публикации.');
                return;
            }

            fetch('create_post.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        content: content
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Публикация успешно добавлена!');
                        window.location.href = '/index.php';
                    } else {
                        alert('Ошибка при добавлении публикации: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Ошибка:', error);
                    alert('Произошла ошибка при добавлении публикации.');
                });
        }
    </script>
</body>

</html>