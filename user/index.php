<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <title>Login / Register User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Jost', sans-serif;
            background: linear-gradient(to bottom, #60a5fa, #2563eb, #93c5fd);
        }
        .main {
            width: 370px;
            background: url("https://doc-08-2c-docs.googleusercontent.com/docs/securesc/68c90smiglihng9534mvqmq1946dmis5/fo0picsp1nhiucmc0l25s29respgpr4j/1631524275000/03522360960922298374/03522360960922298374/1Sx0jhdpEpnNIydS4rnN4kHSJtU1EyWka?e=view&authuser=0&nonce=gcrocepgbb17m&user=03522360960922298374&hash=tfhgbs86ka6divo3llbvp93mg4csvb38") no-repeat center/cover;
            border-radius: 10px;
            box-shadow: 5px 20px 50px #000;
            overflow: hidden;
            padding: 40px 0;
        }
        .tab-content {
            background: rgba(238, 238, 238, 0.95);
            border-radius: 10px;
            padding: 30px 20px 20px 20px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }
        .nav-tabs .nav-link.active {
            background: #2563eb;
            color: #fff !important;
            border-radius: 10px 10px 0 0;
        }
        .nav-tabs .nav-link {
            color: #2563eb;
            font-weight: bold;
        }
        .form-control {
            width: 100%;
            margin: 10px 0;
            border-radius: 5px;
            padding: 10px;
        }
        button {
            width: 100%;
            height: 40px;
            color: #fff;
            background: #2563eb;
            font-size: 1em;
            font-weight: bold;
            margin-top: 20px;
            outline: none;
            border: none;
            border-radius: 5px;
            transition: .2s ease-in;
            cursor: pointer;
        }
        button:hover {
            background: #60a5fa;
        }
    </style>
</head>

<body>
    <div class="main">
        <ul class="nav nav-tabs justify-content-center mb-3" id="loginTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab" aria-controls="login" aria-selected="true">Login</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button" role="tab" aria-controls="register" aria-selected="false">Register</button>
            </li>
        </ul>
        <div class="tab-content" id="loginTabContent">
            <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
                <form action="../auth/process_login_user.php" method="POST">
                    <label class="w-100 text-center mb-4" style="color:#2563eb;font-size:2em;font-weight:bold;">Login</label>
                    <input type="text" name="username" placeholder="Username" class="form-control" required>
                    <input type="password" name="password" placeholder="Password" class="form-control" required>
                    <button type="submit">Login</button>
                </form>
            </div>
            <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="register-tab">
                <form action="../auth/process_signup.php" method="POST">
                    <label class="w-100 text-center mb-4" style="color:#2563eb;font-size:2em;font-weight:bold;">Register</label>
                    <input type="text" name="username" placeholder="Username" class="form-control" required>
                    <input type="email" name="email" placeholder="Email" class="form-control" required>
                    <input type="password" name="password" placeholder="Password" class="form-control" required>
                    <input type="password" name="confirm_password" placeholder="Confirm Password" class="form-control" required>
                    <button type="submit">Register</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>