<?php
session_start();
$username = "";
if(isset($_SESSION['username'])){
    // sudah login ,set variable username dengan data yang sudah ada di session
    $username = $_SESSION['username'];
}else{
    // belum login ,maka redirect ke halaman login
    header('Location:index.php');
}

?>