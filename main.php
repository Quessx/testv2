<?php
require_once "class/Auth.php";
$conn = new Auth();
$conn->checkLogin();
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
    <link rel="stylesheet" href="./view/css/style.css">
    <title>TestV2</title>
</head>
<body class="body-bg">

    <div class="main">Hello <?php echo $conn->echoName() ?>!<p><a href="logout.php">Logout</a></p></div>

<script src="./view/jquery-3.5.1.js"></script>
<script src="view/js/main.js"></script>
</body>
</html>
