<?php
session_start();
header('Content-Type: application/json');
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

require_once '../connection.php';

$response = ['success' => false, 'message' => ''];

if (!$conn) {
    $response['message'] = 'Koneksi ke database gagal.';
    echo json_encode($response);
    exit;
}


// Get form data
$title = $_POST['title'] ?? '';
$author = $_POST['author'] ?? '';
$genre = $_POST['genre'] ?? '';
$isbn = $_POST['isbn'] ?? '';
$published_year = $_POST['published_year'] ?? '';
$total_copies = $_POST['total_copies'] ?? 1;
$description = $_POST['description'] ?? '';

// Basic validation
if (empty($title) || empty($author) || empty($genre) || empty($isbn) || empty($published_year) || empty($total_copies) || empty($description)) {
    $response['message'] = 'Semua field wajib diisi!';
    echo json_encode($response);
    exit;
}

// Validate published year
$currentYear = date('Y');
if ($published_year < 1900 || $published_year > $currentYear) {
    $response['message'] = "Tahun terbit harus antara 1900 dan $currentYear";
    echo json_encode($response);
    exit;
}

// Validate total copies
if ($total_copies < 1) {
    $response['message'] = 'Jumlah salinan minimal 1';
    echo json_encode($response);
    exit;
}

// Process cover image
$cover_image = 'default.jpg';
if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == UPLOAD_ERR_OK) {
    $file = $_FILES['cover_image'];

    // Validate file type
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowedTypes)) {
        $response['message'] = 'Format file tidak didukung. Gunakan JPG, PNG, atau GIF.';
        echo json_encode($response);
        exit;
    }

    // Validate file size (max 2MB)
    $maxSize = 2 * 1024 * 1024; // 2MB
    if ($file['size'] > $maxSize) {
        $response['message'] = 'Ukuran file terlalu besar. Maksimal 2MB.';
        echo json_encode($response);
        exit;
    }

    // Generate unique filename
    $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
    $cover_image = 'cover_' . uniqid() . '.' . $fileExt;
    $uploadPath = '../images/' . $cover_image;

    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
        $response['message'] = 'Gagal mengunggah gambar.';
        echo json_encode($response);
        exit;
    }
}

// Prepare and execute SQL statement
$available_copies = $total_copies;
$stmt = $conn->prepare("INSERT INTO books (title, author, genre, isbn, published_year, description, cover_image, total_copies, available_copies) 
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssii", $title, $author, $genre, $isbn, $published_year, $description, $cover_image, $total_copies, $available_copies);

if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = 'Buku berhasil ditambahkan ke database!';
} else {
    $response['message'] = 'Gagal menyimpan data: ' . $stmt->error;
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>