<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'backend/db.php';

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT login FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    die("Ошибка: не удалось найти пользователя");
}

$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$stmt = $pdo->prepare("SELECT posts.content, users.login FROM posts INNER JOIN users ON posts.author_id = users.id LIMIT :limit OFFSET :offset");
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$posts = $stmt->fetchAll();

$stmt = $pdo->query("SELECT COUNT(*) FROM posts");
$totalPosts = $stmt->fetchColumn();
$totalPages = ceil($totalPosts / $limit);
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TestBlog</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #12263A;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #F0E6D2;
        }

        .navbar a {
            color: #F0E6D2;
            text-decoration: none;
            padding: 10px;
            font-size: 16px;
        }

        .navbar .button {
            background-color: #D4AF89;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            color: #12263A;
        }

        .navbar .button:hover {
            background-color: #B89A6D;
        }

        .container {
            max-width: 1000px;
            margin: 30px auto;
            background-color: #12263A;
            color: #F0E6D2;
            padding: 30px;
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            font-size: 36px;
            margin-bottom: 20px;
        }

        h2 {
            text-align: center;
            font-size: 22px;
            margin-bottom: 40px;
        }

        .post {
            background-color: #1F3B4D;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: flex-start;
        }

        .post-info {
            color: #D4AF89;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .post-content {
            color: #F0E6D2;
            white-space: normal;
            word-wrap: break-word;
            overflow-wrap: break-word;
            padding-right: 20px;
            max-width: 100%;
        }

        button {
            margin-top: 10px;
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .footer {
            text-align: center;
            font-size: 14px;
            color: #D4AF89;
            margin-top: 40px;
        }

        .logout-btn {
            background-color: #D4AF89;
            padding: 10px 20px;
            border-radius: 5px;
            color: #12263A;
            font-size: 16px;
            border: none;
            cursor: pointer;
        }

        .logout-btn:hover {
            background-color: #B89A6D;
        }

        .pagination {
            text-align: center;
            margin-top: 30px;
        }

        .pagination a {
            color: #D4AF89;
            padding: 10px;
            text-decoration: none;
            margin: 0 5px;
            border-radius: 5px;
        }

        .pagination a:hover {
            background-color: #B89A6D;
        }

        .no-posts {
            text-align: center;
            color: #D4AF89;
            font-size: 20px;
            margin-top: 30px;
        }
    </style>
</head>

<body>

    <div class="navbar">
        <h1>TestBlog</h1>
        <div>
            <a href="profile.php"><?= htmlspecialchars($user['login']); ?></a>
            <a href="create_post.php" class="button">Добавить публикацию</a>
            <a href="logout.php" class="button">Выйти</a>
        </div>
    </div>

    <div class="container">
        <h2>Все публикации</h2>

        <?php if (empty($posts)): ?>
            <div class="no-posts">
                Здесь нет постов, станьте первым!
            </div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="post">
                    <div class="post-info">
                        <strong>Автор: <?= htmlspecialchars($post['login']); ?></strong>
                    </div>
                    <div class="post-content">
                        <?= nl2br(htmlspecialchars($post['content'])); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>">&#60; Предыдущая</a>
            <?php endif; ?>
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>">Следующая &#62;</a>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>