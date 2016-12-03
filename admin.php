<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/css/bootstrap.min.css">
    <title>Admin Page</title>
    <style>
    body {
        color: navy !important;;
        background-color: lightblue !important;;
        font-family: "Comic Sans MS", cursive, sans-serif ;
    }
    </style>
</head>
<body>
<h1>Welcome,Admin </h1><br/>
<form action = "" method = "post">
Image Upload
<select name="upload_select_status">
  <option value="blank"> </option>
  <option value="On">ON</option>
  <option value="Off">OFF</option>
</select>
<input type="submit" value="Submit" /></br>
</form>
<br>
<br>
<a href="backup.php"> BackupDB </a>
<br/><br/>
<a href="index.php?logout=1" class="btn btn-primary"> Logout </a>
</body>
<html>

<?php
session_start();
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

$upload_status=$_POST["upload_select_status"];
//echo $upload_status;
if ( $upload_status == "On" )
{
$sql_update_status="update control set upload=1 where id=1";
}
elseif ($upload_status == "blank" )
{
$sql_update_status="select upload from control where id=1";
}
elseif ($upload_status == "Off" )
{
$sql_update_status="update control set upload=0 where id=1";
}
if ($link->query($sql_update_status) === TRUE) {
//    echo "Record updated successfully";
} else {
    "Error updating record: ";
}
?>
