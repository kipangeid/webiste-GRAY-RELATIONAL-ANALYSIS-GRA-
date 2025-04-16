<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once '../koneksi.php';

// ambil data siswa
$siswa = $pdo->query("SELECT * FROM siswa")->fetchAll(PDO::FETCH_ASSOC);

// Ambil data kriteria dan bobot
$kriteria = $pdo->query("SELECT * FROM kriteria")->fetchAll(PDO::FETCH_ASSOC);
$bobot = [];
foreach ($kriteria as $k) {
    $bobot[$k['id']] = $k['bobot'];
}

// Ambil semua nilai siswa
$nilai = [];
foreach ($siswa as $s) {
    $nilai[$s['id']] = [];
    foreach ($kriteria as $k) {
        $stmt = $pdo->prepare("SELECT nilai FROM nilai WHERE id_siswa = ? AND id_kriteria = ?");
        $stmt->execute([$s['id'], $k['id']]);
        $row = $stmt->fetch();
        $nilai[$s['id']][$k['id']] = $row ? (float)$row['nilai'] : 0;
    }
}

// 1. Nilai maksimum per kriteria
$nilaiMax = [];
foreach ($kriteria as $k) {
    $idk = $k['id'];
    $kolomNilai = array_column($nilai, $idk);
    $nilaiMax[$idk] = !empty($kolomNilai) ? max($kolomNilai) : 0;
}

// 2. Hitung selisih absolut Δ
$delta = [];
$delta_min = PHP_FLOAT_MAX;
$delta_max = 0;

foreach ($siswa as $s) {
    foreach ($kriteria as $k) {
        $idk = $k['id'];
        $diff = abs($nilaiMax[$idk] - $nilai[$s['id']][$idk]);
        $delta[$s['id']][$idk] = $diff;

        if ($diff > $delta_max) $delta_max = $diff;
        if ($diff < $delta_min) $delta_min = $diff;
    }
}

// 3. Hitung koefisien relasional ξ dan total GRC
$xi = [];
$grc = [];
$distinguish = 0.5;

foreach ($siswa as $s) {
    $total = 0;
    foreach ($kriteria as $k) {
        $idk = $k['id'];
        $koef = ($delta_min + $distinguish * $delta_max) / ($delta[$s['id']][$idk] + $distinguish * $delta_max);
        $xi[$s['id']][$idk] = $koef;
        $total += $koef * $bobot[$idk];
    }
    $grc[$s['id']] = $total;
}

// 4. Urutkan berdasarkan nilai GRC tertinggi
arsort($grc);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Perhitungan GRA</title>
    <link rel="stylesheet" href="../css/sidebar.css">
</head>

<body>
    <div class="header">
        <h1>Hasil Perhitungan GRA</h1>
    </div>
    <div class="container">
        <div class="sidebar">
            <ul>
                <li><a href="siswa.php">Data Siswa</a></li>
                <li><a href="kriteria.php">Data Kriteria</a></li>
                <li><a href="nilai.php">Input Nilai</a></li>
                <li><a href="perhitungan.php">Perhitungan GRA</a></li>
                <li><a href="hasil.php">Hasil</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="content">
            <h1>Welcome to the Dashboard</h1>
            <h2>Selamat Datang, <?= $_SESSION['username'] ?> (Admin)</h2>
            <p>Here is the main content of the dashboard.</p>
            <table>
                <thead>
                    <tr>
                        <th>Peringkat</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Nilai GRC</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $rank = 1;
                    foreach ($grc as $id_siswa => $nilai_grc):
                        $s = array_filter($siswa, fn($item) => $item['id'] == $id_siswa);
                        $s = array_values($s)[0];
                    ?>
                        <tr>
                            <td><?= $rank++ ?></td>
                            <td><?= htmlspecialchars($s['nama']) ?></td>
                            <td><?= htmlspecialchars($s['kelas']) ?></td>
                            <td><?= number_format($nilai_grc, 4) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="footer">
                <p>muhammadzaeid &copy; 2025</p>
            </div>
</body>

</html>