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

//print_r($endpoint);

//echo "<br/>";
//echo "<br/>";
//echo "begin database";
$link = mysqli_connect($endpoint,"sreddy7","sreddy123","school",3306) or die("Error " . mysqli_error($link));

if (mysqli_connect_errno()) {
    printf("mysql db Connect failed: %s\n", mysqli_connect_error());
    exit();
}

//Drop table
$sql = "drop table loginData";

if ($link->query($sql) === TRUE) {
    //echo "<br/>";
    //echo "Dropping loginData table";
} else {
    //echo "No table is present to drop";
}

//Create Table
$create_table = 'CREATE TABLE IF NOT EXISTS loginData
(
    username VARCHAR(30) NOT NULL,
    password VARCHAR(20) NOT NULL,
    PRIMARY KEY(username)
)';
$create_tbl = $link->query($create_table);
if ($create_table) {
        //echo "<br/>";
        //echo "<b>Table <i>login</i> is created in school database</b>";
        //echo "</br>";
}
else {
      //  echo "error while creating login table!!";
}

//Insert data
$sql = "INSERT INTO loginData (username,password) VALUES ('sreddy7@hawk.iit.edu', 'password'), ('jhajek@iit.edu', 'password')";

if ($link->query($sql) === TRUE) {
    //echo "<br/>";
    //echo "<b>2 New records inserted successfully</b>";
} else {
    //echo "Error while inserting records into login table: " . $sql . "<br>" . $link->error;
}

//Drop table items
$sql = "drop table items";

if ($link->query($sql) === TRUE) {
    //echo "<br/>";
   // echo "Drop items table if present";
} else {
    //echo "No table is present to drop";
}

//Create table items for further use
$create_table_items = 'CREATE TABLE IF NOT EXISTS items
(
   
    id INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(32),
    phone VARCHAR(32),
    s3rawurl VARCHAR(255),
    s3finishedurl VARCHAR(255),
    issubscribed INT NOT NULL,
    status INT,
    receipt VARCHAR(255),
    PRIMARY KEY(id)
)';
$create_tbl = $link->query($create_table_items);
if ($create_table_items) {
        //echo "<br/>";
        //echo "<b>Table items is created successfully</b>";
        //echo "</br>";
}
else {
      //  echo "error while creating items table!!";
}


//move to welcome.php
if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form

      $myusername = mysqli_real_escape_string($link,$_POST['username']);
      $mypassword = mysqli_real_escape_string($link,$_POST['password']);

      $sql = "SELECT * FROM loginData WHERE username = '$myusername' and password = '$mypassword'";
      $result = mysqli_query($link,$sql);
      $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
      //$active = $row['active'];

     $count = mysqli_num_rows($result);
     //echo $count;
      // If result matched $myusername and $mypassword, table row must be 1 row

      if($count > 0) {

         $_SESSION['login_user'] = $myusername;

         header("location: welcome.php");
exit();
      }else {
         $error = "Your Login Credentials are invalid";
      }
   }

$link->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/css/bootstrap.min.css">
    <title>Login Page</title>
    <style>
    body {
        color: navy !important;;
        background-color: lightblue !important;;
        font-family: "Comic Sans MS", cursive, sans-serif ;
    }
    </style>
</head>
<body>
    <div class="container">
        <div class="jumbotron">

            <h1>Login Page</h1><br>
            <form action="index.php" method="POST">
                <label>Username: <input type="text" name="username" size="40"></label><br><br>
                <label>Password: <input type="password" name="password" size="40"></label><br><br>
                <input type="submit" value="Login" class="btn btn-primary">
            </form> 
        </div>
    </div>
</body>
</html>
