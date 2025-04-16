<?php
require 'koneksi.php';

// Data yang ingin diubah (misalnya diambil dari form atau hardcoded dulu)
$username = 'zaeid_panitia';
$new_password = 'panitia_baru123'; // password baru
$new_role = 'admin'; // ubah role juga kalau mau

// Cek apakah user ada
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch();

if (!$user) {
    echo "Username tidak ditemukan.";
} else {
    // Hash password baru
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update password dan role
    $stmt = $pdo->prepare("UPDATE users SET password = :password, role = :role WHERE username = :username");
    $stmt->execute([
        'password' => $hashed_password,
        'role' => $new_role,
        'username' => $username
    ]);

    echo "Data pengguna berhasil diubah.";
}
