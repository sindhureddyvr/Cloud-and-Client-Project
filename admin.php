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

        $endpoint = $result->search('DBInstances[0].Endpoint.Address');
        
        $link = mysqli_connect($endpoint,"sreddy7","sreddy123","school") or die("Error " . mysqli_error($link));
        $result = $link->query('SELECT * FROM items');
        //Exporting database
        $fp = fopen('php://output', 'w');
        if ($fp && $result) {
                header('Content-Type: text/csv');
                header('Content-Disposition: attachment; filename="export.csv"');

		$fields = array('id','email','phone','s3rawurl','s3finishedurl','issubscribed','status','receipt');
                fputcsv($fp,$fields);
                while ($row = $result->fetch_array(MYSQLI_NUM)) {
                fputcsv($fp, array_values($row));
                }
                die;
        }
	$link->close();
?>



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
</body>
<html>
