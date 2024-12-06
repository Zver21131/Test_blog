<?php
include 'backend/db.php';

$response = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);

    $stmt = $pdo->prepare("SELECT password_encrypted, `key` FROM users WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {

        $encrypted_password = base64_decode($user['password_encrypted']);
        $encryption_key = $user['key'];

        $iv_length = openssl_cipher_iv_length('aes-256-cbc');
        $iv = substr($encrypted_password, 0, $iv_length);
        $ciphertext = substr($encrypted_password, $iv_length);

        $decrypted_password = openssl_decrypt($ciphertext, 'aes-256-cbc', $encryption_key, 0, $iv);

        if ($decrypted_password !== false) {
            $response = "Ваш пароль: <b>" . htmlspecialchars($decrypted_password) . "</b>";
        } else {
            $response = "Не удалось расшифровать пароль. Попробуйте позже.";
        }
    } else {
        $response = "Пользователь с таким логином не найден.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Восстановление пароля</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #1a1e2b;
            padding: 30px 40px;
            border-radius: 15px;
            width: 400px;
            text-align: center;
        }

        h1 {
            font-size: 26px;
            margin-bottom: 20px;
            color: #d6c2a6;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-size: 16px;
            color: #d6c2a6;
            text-align: left;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-bottom: 20px;
            border: none;
            border-radius: 5px;
            background-color: #fff;
            color: #333;
        }

        button {
            background-color: #8c6a48;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #705339;
        }

        .response {
            margin-top: 15px;
            font-size: 16px;
            color: #d6c2a6;
        }

        .back-button {
            display: inline-block;
            text-decoration: none;
            color: #d6c2a6;
            margin-top: 10px;
            font-size: 16px;
            text-align: center;
        }

        .back-button:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>Восстановление</h1>

        <form method="POST">
            <label for="login">Логин</label>
            <input type="text" id="login" name="login" value="<?= htmlspecialchars($_POST['login'] ?? '') ?>" required>

            <div class="response">
                <?= $response ?>
            </div>

            <button type="submit">Подтвердить</button>
        </form>

        <a href="login.php" class="back-button">Вернуться на страницу входа</a>
    </div>

</body>

</html>