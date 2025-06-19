<?php session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
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
            width: 350px;
            background: url("https://doc-08-2c-docs.googleusercontent.com/docs/securesc/68c90smiglihng9534mvqmq1946dmis5/fo0picsp1nhiucmc0l25s29respgpr4j/1631524275000/03522360960922298374/03522360960922298374/1Sx0jhdpEpnNIydS4rnN4kHSJtU1EyWka?e=view&authuser=0&nonce=gcrocepgbb17m&user=03522360960922298374&hash=tfhgbs86ka6divo3llbvp93mg4csvb38") no-repeat center/cover;
            border-radius: 10px;
            box-shadow: 5px 20px 50px #000;
            overflow: hidden;
            padding: 40px 0;
        }

        .login {
            background: rgba(238, 238, 238, 0.95);
            border-radius: 10px;
            padding: 30px 20px 20px 20px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }

        .login label {
            color: #2563eb;
            font-size: 2em;
            font-weight: bold;
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
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
        <div class="login">
            <form action="../auth/process_login_admin.php" method="POST">
                <label>Login</label>
                <input type="text" name="username" placeholder="Username" class="form-control" required>
                <input type="password" name="password" placeholder="Password" class="form-control" required>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>

</html>
