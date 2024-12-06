<?php
session_start();

require_once 'backend/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['login'], $data['password'])) {
        $login = trim($data['login']);
        $password = trim($data['password']);

        $stmt = $pdo->prepare("SELECT id, password_hash FROM users WHERE login = ?");
        $stmt->execute([$login]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];

            echo json_encode(['success' => true]);
            exit;
        } else {

            echo json_encode(['error' => 'Неверный логин или пароль']);
            exit;
        }
    } else {
        echo json_encode(['error' => 'Отсутствуют логин или пароль']);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #12263A;
            color: #F0E6D2;
            padding: 20px;
            border-radius: 8px;
            width: 300px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .login-container input {
            width: calc(100% - 20px);
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            border: none;
            font-size: 14px;
        }

        .login-container button {
            width: calc(100% - 20px);
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #D4AF89;
            color: #12263A;
            font-size: 16px;
            cursor: pointer;
        }

        .login-container .links {
            margin-top: 15px;
        }

        .login-container .links a {
            color: #D4AF89;
            text-decoration: none;
            font-size: 14px;
        }

        .login-container .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Вход</h2>
        <form id="loginForm">
            <input type="text" id="login" name="login" placeholder="Логин" required>
            <input type="password" id="password" name="password" placeholder="Пароль" required>
            <button type="button" onclick="submitForm()">Войти</button>
        </form>
        <div class="links">
            <a href="register.php">Регистрация</a> | <a href="forgot_password.php">Забыли пароль?</a>
        </div>
    </div>

    <script>
        async function submitForm() {
            const baseURL = `${window.location.protocol}//${window.location.host}`;
            const login = document.getElementById('login').value;
            const password = document.getElementById('password').value;

            const response = await fetch('/login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    login,
                    password
                })
            });

            const result = await response.json();
            console.log(result);

            if (result.success) {
                alert('Вход выполнен!');
                window.location.href = '/index.php';
            } else {
                alert(result.error);
            }
        }
    </script>
</body>

</html>