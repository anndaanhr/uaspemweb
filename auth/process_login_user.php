<?php
session_start();
include '../connection.php';

$username = $_POST['username'];
$password = $_POST['password'];

// Hash password dengan SHA256
$hashedPassword = hash('sha256', $password);

// Ambil user berdasarkan username dan password yang sudah di-hash
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
$stmt->bind_param("ss", $username, $hashedPassword);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    // Cek apakah rolenya 'user'
    if ($user["role"] === 'user') {
        $_SESSION["username"] = $user["username"];
        $_SESSION["role"] = $user["role"];
        header('Location: ../user/dashboard.php');
        exit();
    } else {
        echo '<script>alert("Login ditolak. Hanya user yang diizinkan.");</script>';
        echo '<script>window.location="../user/index.php";</script>';
    }
} else {
    echo '<script>alert("Login gagal. Username atau password salah.");</script>';
    echo '<script>window.location="../user/index.php";</script>';
}

$stmt->close();
$conn->close();
?>