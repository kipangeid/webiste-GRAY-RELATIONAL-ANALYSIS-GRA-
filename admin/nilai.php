<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

require_once '../koneksi.php';

// Ambil data siswa dan kriteria
$siswa = $pdo->query("SELECT * FROM siswa")->fetchAll(PDO::FETCH_ASSOC);
$kriteria = $pdo->query("SELECT * FROM kriteria")->fetchAll(PDO::FETCH_ASSOC);

// Simpan nilai ke database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_siswa = $_POST['id_siswa'];

    // Hapus nilai lama
    $pdo->prepare("DELETE FROM nilai WHERE id_siswa = ?")->execute([$id_siswa]);

    // Masukkan nilai baru
    foreach ($kriteria as $k) {
        $id_kriteria = $k['id'];
        $nilai = $_POST['nilai'][$id_kriteria];
        $stmt = $pdo->prepare("INSERT INTO nilai (id_siswa, id_kriteria, nilai) VALUES (?, ?, ?)");
        $stmt->execute([$id_siswa, $id_kriteria, $nilai]);
    }

    echo "<script>alert('Nilai berhasil disimpan!');</script>";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Input Nilai</title>
    <link rel="stylesheet" href="../css/sidebar.css">
</head>

<body>
    <div class="header">
        <h1>Input Nilai Siswa</h1>
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
            <h2>Silakan Masukkan Nilai Siswa</h2>
            <form method="post" class="form-nilai">
                <div class="form-group">
                    <label for="id_siswa">Pilih Siswa:</label>
                    <select name="id_siswa" id="id_siswa" required>
                        <option value="">-- Pilih Siswa --</option>
                        <?php foreach ($siswa as $s): ?>
                            <option value="<?= $s['id'] ?>"><?= $s['nama'] ?> (<?= $s['kelas'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <h3>Nilai Kriteria:</h3>
                <?php foreach ($kriteria as $k): ?>
                    <div class="form-group">
                        <label><?= $k['nama_kriteria'] ?>:</label>
                        <input type="number" name="nilai[<?= $k['id'] ?>]" step="0.01" required>
                    </div>
                <?php endforeach; ?>

                <div class="form-group">
                    <button type="submit">Simpan Nilai</button>
                </div>
            </form>
        </div>

    </div>

    <div class="footer">
        <p>muhammadzaeid &copy; 2025</p>
    </div>
</body>

</html>