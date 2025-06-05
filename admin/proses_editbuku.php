<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}

include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id             = intval($_POST['id']);
    $title          = mysqli_real_escape_string($conn, $_POST['title']);
    $author         = mysqli_real_escape_string($conn, $_POST['author']);
    $genre          = mysqli_real_escape_string($conn, $_POST['genre']);
    $isbn           = mysqli_real_escape_string($conn, $_POST['isbn']);
    $published_year = intval($_POST['published_year']);
    $total_copies   = intval($_POST['total_copies']);
    $description    = mysqli_real_escape_string($conn, $_POST['description']);

    // Ambil data buku sebelumnya
    $query_old = "SELECT cover_image FROM books WHERE id = $id";
    $result_old = mysqli_query($conn, $query_old);
    $old = mysqli_fetch_assoc($result_old);
    $old_cover = $old['cover_image'];

    $cover_image = $old_cover; // Default ke cover lama

    // Jika ada file baru diupload
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
        $fileTmp  = $_FILES['cover_image']['tmp_name'];
        $fileName = basename($_FILES['cover_image']['name']);
        $fileExt  = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed  = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExt, $allowed)) {
            $newFileName = uniqid() . '.' . $fileExt;
            $uploadPath = '../images/' . $newFileName;

            if (move_uploaded_file($fileTmp, $uploadPath)) {
                $cover_image = $newFileName;

                // Hapus gambar lama jika bukan default/placeholder
                if ($old_cover && file_exists("../images/" . $old_cover)) {
                    unlink("../images/" . $old_cover);
                }
            }
        }
    }

    // Update data buku
    $query = "UPDATE books SET 
        title = '$title',
        author = '$author',
        genre = '$genre',
        isbn = '$isbn',
        published_year = $published_year,
        total_copies = $total_copies,
        description = '$description',
        cover_image = '$cover_image'
        WHERE id = $id";

    if (mysqli_query($conn, $query)) {
        header('Location: kelolabuku.php?status=success');
    } else {
        echo "Gagal mengupdate buku: " . mysqli_error($conn);
    }
} else {
    header('Location: kelolabuku.php');
    exit;
}
?>
