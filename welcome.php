<?php
session_start();
$username = $_SESSION['login_user'];
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/css/bootstrap.min.css">
    <title>Welcome Page</title>
    <style>
    body {
        color: navy !important;;
        background-color: lightblue !important;;
        font-family: "Comic Sans MS", cursive, sans-serif ;
    }
    </style>
</head>
<body>
<h1>Welcome </h1><br/>

<a href="gallery.php"> Gallery</a>
<a href="upload.php">Upload</a>
<a href="admin.php">Admin</a><br/><br/><br/>
<a href="index.php?logout=1" class="btn btn-primary"> Logout</a>
</body>
</html>
