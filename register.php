<?php
require_once 'db.php';

if (isset($_SESSION['user_id'])) {
    header('Location: profile.php');
    exit;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm'] ?? '';

    if ($username === '' || $email === '' || $password === '' || $confirm === '') {
        $errors[] = 'Заполните все поля.';
    }
    if (mb_strlen($username) < 3) {
        $errors[] = 'Логин минимум 3 символа.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Некорректный email.';
    }
    if (strlen($password) < 6) {
        $errors[] = 'Пароль минимум 6 символов.';
    }
    if ($password !== $confirm) {
        $errors[] = 'Пароли не совпадают.';
    }

    // проверка на дубликат
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $errors[] = 'Такой логин или email уже занят.';
        }
    }

    // сохранение
    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $hash]);

        header('Location: index.php?success=1');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="box">
    <h1>Регистрация</h1>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $e): ?>
                <p><?= htmlspecialchars($e) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" id="registerForm">
        <input type="text" name="username" placeholder="Логин"
               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required>
        <input type="email" name="email" placeholder="Email"
               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
        <input type="password" name="password" placeholder="Пароль (мин. 6)" required>
        <input type="password" name="confirm" placeholder="Повтор пароля" required>
        <button type="submit">Зарегистрироваться</button>
    </form>

    <p class="center">Уже есть аккаунт? <a href="index.php">Войти</a></p>
</div>
<script src="script.js"></script>
</body>
</html>