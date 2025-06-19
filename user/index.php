<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/daftar.css">
</head>

<body>
    <div class="main">
        <input type="checkbox" id="chk" aria-hidden="true">

        <div class="signup">
            <form action="../auth/process_signup.php" method="POST">
                <label for="chk" aria-hidden="true">Sign up</label>
                <input type="text" name="username" placeholder="Username" class="form-control" required>
                <input type="password" name="password" placeholder="Password" class="form-control" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" class="form-control"
                    required>
                <button type="submit">Sign up</button>
            </form>

        </div>

        <div class="login">
            <form action="../auth/process_login_user.php" method="POST">
                <label for="chk" aria-hidden="true">Login</label>
                <input type="text" name="username" placeholder="Username" class="form-control" required>
                <input type="password" name="password" placeholder="Password" class="form-control" required>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>

</html>