<?php
session_start();
include 'db.php'; // Pastikan file db.php ada di direktori yang sama

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header("Location: admin/home.php");
        } else {
            header("Location: user/dashboard.php");
        }
        exit;
    } else {
        echo "Password salah.";
    }
} else {
    echo "Username tidak ditemukan.";
}
?>
