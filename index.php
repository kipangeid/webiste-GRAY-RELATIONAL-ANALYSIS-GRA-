<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>

<body>
    <h1>Selamat datang, <?= $_SESSION['username']; ?>!</h1>
    <a href="logout.php">Logout</a>
</body>

</html>