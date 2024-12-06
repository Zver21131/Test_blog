<?php
require_once 'backend/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (isset($data['login'], $data['password'])) {
        $login = trim($data['login']);
        $password = trim($data['password']);

        $stmt = $pdo->prepare("SELECT id FROM users WHERE login = ?");
        $stmt->execute([$login]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['error' => 'Логин уже занят']);
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $key = bin2hex(random_bytes(16));

        $iv = random_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $passwordEncrypted = base64_encode($iv . openssl_encrypt($password, 'aes-256-cbc', $key, 0, $iv));

        $stmt = $pdo->prepare("INSERT INTO users (login, password_hash, password_encrypted, `key`) VALUES (?, ?, ?, ?)");
        $stmt->execute([$login, $hashedPassword, $passwordEncrypted, $key]);

        echo json_encode(['success' => true]);
        exit;
    } else {
        echo json_encode(['error' => 'Некорректные данные: отсутствуют логин или пароль']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
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

        .register-container {
            background-color: #12263A;
            color: #F0E6D2;
            padding: 20px;
            border-radius: 8px;
            width: 300px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .register-container input {
            width: calc(100% - 20px);
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 5px;
            border: none;
            font-size: 14px;
        }

        .register-container button {
            width: calc(100% - 20px);
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #D4AF89;
            color: #12263A;
            font-size: 16px;
            cursor: pointer;
        }

        .register-container .links {
            margin-top: 15px;
        }

        .register-container .links a {
            color: #D4AF89;
            text-decoration: none;
            font-size: 14px;
        }

        .register-container .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <h2>Регистрация</h2>
        <form id="registerForm">
            <input type="text" id="login" name="login" placeholder="Логин" required>
            <input type="password" id="password" name="password" placeholder="Пароль" required>
            <button type="button" onclick="submitForm()">Зарегистрироваться</button>
        </form>
        <div class="links">
            <a href="login.php">Вернуться на вход</a>
        </div>
    </div>

    <script>
        async function submitForm() {
            const login = document.getElementById('login').value;
            const password = document.getElementById('password').value;

            try {
                const response = await fetch('register.php', {
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
                if (result.success) {
                    alert('Регистрация успешна!');
                    window.location.href = 'login.php';
                } else {
                    alert(result.error);
                }
            } catch (error) {
                alert('Ошибка при отправке запроса. Попробуйте позже.');
                console.error(error);
            }
        }
    </script>
</body>

</html>