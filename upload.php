<?php
session_start();
$username = $_SESSION['login_user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/css/bootstrap.min.css">
    <title>Upload Page</title>
    <style>
    body {
        color: navy !important;;
        background-color: lightblue !important;;
        font-family: "Comic Sans MS", cursive, sans-serif ;
    }
    </style>
</head>
<body>
<h1>Welcome to the Upload </h1><br/>
<form enctype="multipart/form-data" action="uploader.php" method="POST">
    <!-- MAX_FILE_SIZE must precede the file input field -->
    <input type="hidden" name="MAX_FILE_SIZE" value="3000000" />

<input type="file" name="userfile" />
<input type="submit" value="submit" />
</form>
<br>
<br>
<a href="welcome.php" class="btn btn-primary"> BackToHome</a>
<a href="index.php?logout=1" class="btn btn-primary"> Logout</a>
</body>
<html>
