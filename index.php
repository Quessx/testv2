<?php
session_start();
require_once "class/Auth.php";
$check = new Auth();
$check->logout();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./view/css/style.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Rajdhani&display=swap" rel="stylesheet">
    <title>TestV2</title>
</head>
<body>

    <form>
        <label>Login</label>
        <input type="text" name="login" id="login">
        <label>Password</label>
        <input type="password" name="password" id="password">
        <button type="submit" class="login-btn" name="login-btn">Login</button>
        <p>Don’t have an account? - <a href="register.php">Sign Up!</a></p>
        <p class="msg none">Lorem ipsum dolor sit.</p>
    </form>

    <script src="view/jquery-3.5.1.js"></script>
    <script src="view/js/main.js"></script>

</body>
</html>