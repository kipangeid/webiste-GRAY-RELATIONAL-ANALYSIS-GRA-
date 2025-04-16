<?php
require_once '../vendor/autoload.php';
require_once '../koneksi.php';

use Dompdf\Dompdf;

// Ambil data siswa dan kriteria
$siswa = $pdo->query("SELECT * FROM siswa")->fetchAll(PDO::FETCH_ASSOC);
$kriteria = $pdo->query("SELECT * FROM kriteria")->fetchAll(PDO::FETCH_ASSOC);

// Buat bobot kriteria
$bobot = [];
foreach ($kriteria as $k) {
    $bobot[$k['id']] = $k['bobot'];
}

// Ambil nilai siswa
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

// Hitung nilai maksimum per kriteria
$nilaiMax = [];
foreach ($kriteria as $k) {
    $idk = $k['id'];
    $kolomNilai = array_column($nilai, $idk);
    $nilaiMax[$idk] = !empty($kolomNilai) ? max($kolomNilai) : 0;
}

// Hitung Δ (delta)
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

// Hitung nilai GRC
$grc = [];
$xi = [];
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

// Urutkan hasil GRC
arsort($grc);

// Buat HTML untuk PDF
$html = '
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            font-family: Arial, sans-serif;
        }
        th {
            background-color: #4CAF50;
            color: white;
            padding: 8px;
            text-align: left;
            border: 2px solid #333;
        }
        td {
            padding: 8px;
            border: 2px solid #333;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        h1 {
            text-align: center;
        }
    </style>

    <h1>Hasil Perhitungan GRA untuk Penerima Beasiswa</h1>
    <table>
        <thead>
            <tr>
                <th>Peringkat</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Nilai GRC</th>
            </tr>
        </thead>
        <tbody>';


$rank = 1;
foreach ($grc as $id_siswa => $nilai_grc) {
    // Ambil data siswa
    $s = array_filter($siswa, fn($item) => $item['id'] == $id_siswa);
    $s = array_values($s);
    if (empty($s)) continue;
    $s = $s[0];

    $html .= "<tr>
                <td>{$rank}</td>
                <td>{$s['nama']}</td>
                <td>{$s['kelas']}</td>
                <td>" . number_format($nilai_grc, 4) . "</td>
              </tr>";
    $rank++;
}

$html .= '</tbody></table>';

// Generate PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("laporan_beasiswa.pdf", array("Attachment" => false));
