<?php
session_start();
$username = $_SESSION['login_user'];

require 'vendor/autoload.php';

use Aws\Rds\RdsClient;
$client = RdsClient::factory(array(
'version' => 'latest',
'region'  => 'us-west-2'
));

$result = $client->describeDBInstances(array(
    'DBInstanceIdentifier' => 'sreddy7',
));
$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];

$link = mysqli_connect($endpoint,"sreddy7","sreddy123","school",3306) or die("Error " . mysqli_error($link));
if (mysqli_connect_errno()) {
    printf("mysql db Connect failed: %s\n", mysqli_connect_error());
    exit();
}


$link->real_query("Select upload from control where id=1");
$res = $link->use_result();
while ($row = $res->fetch_assoc()) {
    $value = $row['upload'];
}
$link->close();

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
<h2>Welcome, <?php echo $username ?> </h2><br/>
<?php 
if($username == "controller")
{ ?>
<a href="gallery.php"> Gallery</a>
<a href="upload.php">Upload</a>
<a href="admin.php">Admin</a><br/><br/><br/>
<a href="index.php?logout=1" class="btn btn-primary"> Logout</a>
<?php }
elseif($value== "1") {
?>
<a href="gallery.php"> Gallery</a>
<a href="upload.php">Upload</a><br/><br/><br/>
<a href="index.php?logout=1" class="btn btn-primary"> Logout</a>
<?php
}
else{
?>
<a href="gallery.php"> Gallery</a><br/><br/>
<a href="index.php?logout=1" class="btn btn-primary"> Logout</a>
<?php
}
?>
</body>
</html>

use Aws\Rds\RdsClient;
$client = RdsClient::factory(array(
'version' => 'latest',
'region'  => 'us-west-2'
));

$result = $client->describeDBInstances(array(
    'DBInstanceIdentifier' => 'sreddy7',
));
$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];

$link = mysqli_connect($endpoint,"sreddy7","sreddy123","school",3306) or die("Error " . mysqli_error($link));
if (mysqli_connect_errno()) {
    printf("mysql db Connect failed: %s\n", mysqli_connect_error());
    exit();
}


$link->real_query("Select upload from control where id=1");
$res = $link->use_result();
while ($row = $res->fetch_assoc()) {
    $value = $row['upload'];
}
$link->close();

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
<h2>Welcome, <?php echo $username ?> </h2><br/>
<?php 
if($username == "controller")
{ ?>
<a href="gallery.php"> Gallery</a>
<a href="upload.php">Upload</a>
<a href="admin.php">Admin</a><br/><br/><br/>
<a href="index.php?logout=1" class="btn btn-primary"> Logout</a>
<?php }
elseif($value== "1") {
?>
<a href="gallery.php"> Gallery</a>
<a href="upload.php">Upload</a><br/><br/><br/>
<a href="index.php?logout=1" class="btn btn-primary"> Logout</a>
<?php
}
else{
?>
<a href="gallery.php"> BackHome</a><br/><br/>
<a href="index.php?logout=1" class="btn btn-primary"> Logout</a>
<?php
}
?>
</body>
</html>
