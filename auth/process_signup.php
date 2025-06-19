<?php
session_start();
include '../connection.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi dasar
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Password dan konfirmasi tidak sama.";
        header("Location: ../user/index.php");
        exit();
    }

    // Cek username sudah dipakai atau belum
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = "Username sudah digunakan.";
        header("Location: ../user/index.php");
        exit();
    }

    // Hash password dan simpan
    $hashed_password = hash('sha256', $password);
    $role = 'user';

    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $hashed_password, $role);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Akun berhasil dibuat. Silakan login.";
        header("Location: ../user/index.php");
    } else {
        $_SESSION['error'] = "Gagal mendaftar. Coba lagi.";
        header("Location: ../user/index.php");
    }
}
?>