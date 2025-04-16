<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once '../koneksi.php';

// Tambah data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'] ?? '';
    $kelas = $_POST['kelas'] ?? '';
    $nis = $_POST['nis'] ?? '';

    $stmt = $pdo->prepare("INSERT INTO siswa (nama, kelas, nis) VALUES (?, ?, ?)");
    $stmt->execute([$nama, $kelas, $nis]);
}

// Hapus data
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = $pdo->prepare("DELETE FROM siswa WHERE id = ?");
    $stmt->execute([$id]);
}

// Ambil data siswa
$stmt = $pdo->query("SELECT * FROM siswa");
$siswaList = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Siswa</title>
    <link rel="stylesheet" href="../css/sidebar.css">
</head>

<body>
    <div class="header">
        <h1>Data siswa</h1>
    </div>
    <div class="container">
        <div class="sidebar">
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="siswa.php">Data Siswa</a></li>
                <li><a href="kriteria.php">Data Kriteria</a></li>
                <li><a href="nilai.php">Input Nilai</a></li>
                <li><a href="perhitungan.php">hasil Perhitungan</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="content">
            <h2>silahkan masukkan data siswa</h2>
            <form method="post">
                <label for="id_siswa">Nama Siswa:</label>
                <input type="text" name="nama" placeholder="Nama Siswa" required>
                <label for="id_siswa">Kelas Siswa:</label>
                <input type="text" name="kelas" placeholder="Kelas" required>
                <label for="id_siswa">NIS Siswa:</label>
                <input type="text" name="nis" placeholder="NIS" required>
                <button type="submit">Tambah Siswa</button>
            </form>

            <table border="1" cellpadding="10" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>NIS</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($siswaList as $index => $siswa): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($siswa['nama']) ?></td>
                            <td><?= htmlspecialchars($siswa['kelas']) ?></td>
                            <td><?= htmlspecialchars($siswa['nis']) ?></td>
                            <td><a href="?hapus=<?= $siswa['id'] ?>" onclick="return confirm('Hapus data ini?')">Hapus</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>

    <div class="footer">
        <p>muhammadzaeid &copy; 2025</p>
    </div>
</body>

</html>