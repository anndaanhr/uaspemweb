<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

include '../connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Ambil data gambar sebelum dihapus
    $getQuery = "SELECT cover_image FROM books WHERE id = $id";
    $getResult = mysqli_query($conn, $getQuery);
    $book = mysqli_fetch_assoc($getResult);

    if ($book) {
        // Hapus gambar dari folder (jika ada)
        if (!empty($book['cover_image']) && file_exists('../images/' . $book['cover_image'])) {
            unlink('../images/' . $book['cover_image']);
        }

        // Hapus data buku dari database
        $deleteQuery = "DELETE FROM books WHERE id = $id";
        if (mysqli_query($conn, $deleteQuery)) {
            header("Location: kelolabuku.php?success=1");
            exit;
        } else {
            echo "Gagal menghapus buku.";
        }
    } else {
        echo "Buku tidak ditemukan.";
    }
} else {
    echo "ID tidak ditemukan.";
}
?>
