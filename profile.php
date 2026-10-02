<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare("SELECT username, email, created_at FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Профиль</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="box">
    <h1>Личный кабинет</h1>
    <p>Привет, <strong><?= htmlspecialchars($user['username']) ?></strong>!</p>
    <p>Email: <?= htmlspecialchars($user['email']) ?></p>
    <p>Дата регистрации: <?= htmlspecialchars($user['created_at']) ?></p>
    <a href="logout.php" class="logout">Выйти</a>
</div>
</body>
</html>