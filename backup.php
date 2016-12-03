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
