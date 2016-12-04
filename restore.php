<?php

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
$restore_file  = "/tmp/backup.sql";
$username      = "sreddy7";
$password      = "sreddy123";
$database_name = "school";

$cmd = "mysql -h {$endpoint} -u {$username} -p{$password} {$database_name} < $restore_file";
exec($cmd);

?>

<html>
<head>
<meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.4/css/bootstrap.min.css">
<title>Db backup Page</title>
<style>
    body {
        color: navy !important;;
        background-color: lightblue !important;;
        font-family: "Comic Sans MS", cursive, sans-serif ;
    }
    </style>
</head>
<body>
<h2>Welcome to DB restore Page</h2><br/>
<?php
echo "Restore successfull";
echo "<br/>";
?>
<br>
<a href="welcome.php" class="btn btn-primary"> BackToHome</a> 
<a href="admin.php" class="btn btn-primary"> Admin </a>
</body>
</html>
