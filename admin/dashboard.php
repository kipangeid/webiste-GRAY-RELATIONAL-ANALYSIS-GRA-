<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../css/sidebar.css">
</head>

<body>
    <div class="header">
        <h1>Dashboard</h1>
    </div>
    <div class="container">
        <div class="sidebar">
            <ul>
                <li><a href="siswa.php">Data Siswa</a></li>
                <li><a href="kriteria.php">Data Kriteria</a></li>
                <li><a href="nilai.php">Input Nilai</a></li>
                <li><a href="perhitungan.php">hasil Perhitungan</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="content">
            <div class="dashboard-card">
                <h2>Selamat Datang, <?= $_SESSION['username'] ?> (Admin)</h2>
                <p>Ini adalah website penelitian perhitungan penerima beasiswa di <strong>SMA N 9 KERINCI</strong> menggunakan metode <strong>Gray Relational Analysis (GRA)</strong>.</p>
                <img src="/beasiswa%20gra/img/saya.jpeg" style="width:150px; height:190px; border-radius:20px; margin-bottom:20px;">
                <h4>Nama : <?= $_SESSION['username'] ?> (Admin)</h4>
            </div>
        </div>

    </div>
    <div class="footer">
        <p>muhammadzaeid &copy; 2025</p>
    </div>
</body>

</html>