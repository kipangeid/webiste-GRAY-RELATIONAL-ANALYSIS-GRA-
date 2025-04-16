<?php
require 'koneksi.php';  // Pastikan file koneksi.php sudah benar

$username = 'zaeid_panitia';
$password = 'panitia1234';
$role = 'panitia';

// Cek apakah username sudah ada
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
$stmt->execute(['username' => $username]);
$user = $stmt->fetch();

if ($user) {
    echo "Username sudah terdaftar.";
} else {
    // Hash password sebelum menyimpan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert data baru
    $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (:username, :password, :role)");
    $stmt->execute([
        'username' => $username,
        'password' => $hashed_password,
        'role' => $role
    ]);
    echo "Pengguna berhasil ditambahkan.";
}
