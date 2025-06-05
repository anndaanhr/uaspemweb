<?php
    session_start();
if (isset($_SESSION['username'])) {
    // Jika sudah login, langsung alihkan ke home.php
    header('Location: home.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    INI FORM POST
    <form method="POST" action="../auth/process_login_admin.php">
            <input name="username" type="text" placeholder="Masukan Username"/>
            <input name="password" type="password" placeholder="Masukan Password"/>
            <button type="submit" >SUBMIT</button>
    </form> 
</body>
</html>