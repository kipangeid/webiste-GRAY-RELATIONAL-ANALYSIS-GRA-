<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once '../koneksi.php';

// Tambah kriteria
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_kriteria = $_POST['nama_kriteria'] ?? '';
    $bobot = $_POST['bobot'] ?? '';
    $tipe = $_POST['tipe'] ?? 'benefit';

    $stmt = $pdo->prepare("INSERT INTO kriteria (nama_kriteria, bobot, tipe) VALUES (?, ?, ?)");
    $stmt->execute([$nama_kriteria, $bobot, $tipe]);
}

// Hapus kriteria
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = $pdo->prepare("DELETE FROM kriteria WHERE id = ?");
    $stmt->execute([$id]);
}

// Ambil data kriteria
$stmt = $pdo->query("SELECT * FROM kriteria");
$kriteriaList = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Kriteria</title>
    <link rel="stylesheet" href="../css/sidebar.css">
</head>

<body>

    <div class="header">
        <h1>Data Kriteria Beasiswa</h1>
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
            <h2>silahkan masukkan data kriteria siswa</h2>
            <form method="post">
                <input type="text" name="nama_kriteria" placeholder="Nama Kriteria" required>
                <input type="number" step="0.01" name="bobot" placeholder="Bobot (misal: 0.3)" required>
                <select name="tipe" required>
                    <option value="benefit">Benefit</option>
                    <option value="cost">Cost</option>
                </select>
                <button type="submit">Tambah Kriteria</button>
            </form>

            <table border="1" cellpadding="10" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kriteria</th>
                        <th>Bobot</th>
                        <th>Tipe</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kriteriaList as $index => $kriteria): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= htmlspecialchars($kriteria['nama_kriteria']) ?></td>
                            <td><?= htmlspecialchars($kriteria['bobot']) ?></td>
                            <td><?= htmlspecialchars($kriteria['tipe']) ?></td>
                            <td><a href="?hapus=<?= $kriteria['id'] ?>" onclick="return confirm('Hapus kriteria ini?')">Hapus</a></td>
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