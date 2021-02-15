<?php
require_once "class/Auth.php";
$check = new Auth();
$check->logout();

?><!doctype html>
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
    <a class="popup-open" href="#">Рекомендуется к прочтению</a>

    <div class="popup-fade">
        <div class="popup">
            <a class="popup-close" href="#">Закрыть</a>
            <p>
            <p> Для того чтобы зарегестрироваться нужно: </p>
            <p> - login (unique)    [валидация : минимум 6 символов , только буквы и цифры] </p>
            <p> - password          [валидация : минимум 6 символов , обязательно должны содержать цифру, буквы в разных регистрах и спец символ (знаки)] </p>
            <p> - confirm_password </p>
            <p> - email (unique)    [валидация : email] </p>
            <p> - name              [валидация : 2 символа , только буквы и цифры] </p>
            </p>
        </div>
    </div>

    <label>Login</label>
    <input type="text" name="login" id="login">
    <label>Email</label>
    <input type="text" name="email" id="email">
    <label>Password</label>
    <input type="password" name="password" id="password">
    <label>Confirm Password</label>
    <input type="password" name="password_confirm" id="password_confirm">
    <label>Name</label>
    <input type="text" name="username" id="username">
    <button type="submit" name="register-btn" class="register-btn">Register</button>
    <p>Already have an account? - <a href="index.php">Sign In!</a></p>
    <p class="msg none">Lorem ipsum dolor sit.</p>
</form>

<script src="./view/jquery-3.5.1.js"></script>
<script src="view/js/main.js"></script>
<script src="view/js/popup.js"></script>
</body>
</html>
