<?php
include 'db.php'; // Include file koneksi database

// Ambil data dari form
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Enkripsi
$role = "user"; // default role

// Simpan ke database
$sql = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $username, $password, $role);

if ($stmt->execute()) {
    echo "Registrasi berhasil dengan role user!";
} else {
    echo "Gagal registrasi: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
