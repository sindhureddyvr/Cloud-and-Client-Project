<?php
session_start();
require 'vendor/autoload.php';
$s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'region'  => 'us-west-2'
]);
// have to hard code this here because index.php doesn't exist
//$_SESSION['email'] = "hajek@iit.edu";
echo "\n" . $_SESSION['login_user'] ."<br/>";

// Retrieve the POSTED file information (location, name, etc, etc)
$uploaddir = '/tmp/';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
#echo '<pre>';
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
    echo "File is valid, and was successfully uploaded.\n"."<br/>";
} else {
    echo "Possible file upload attack!\n";
}
//echo 'Here is some more debugging info:';
//print_r($_FILES);
// Upload file to S3 bucket
$s3result = $s3->putObject([
    'ACL' => 'public-read',
     'Bucket' => 'raw-svr',
      'Key' =>  basename($_FILES['userfile']['name']),
      'SourceFile' => $uploadfile 
// Retrieve URL of uploaded Object
]);
$url=$s3result['ObjectURL'];
echo "\n". "This is your URL: " . $url ."<br/>";
// INSERT SQL record of job information
$rdsclient = new Aws\Rds\RdsClient([
  'region'            => 'us-west-2',
    'version'           => 'latest'
]);
$rdsresult = $rdsclient->describeDBInstances([
    'DBInstanceIdentifier' => 'sreddy7'
]);
$endpoint = $rdsresult['DBInstances'][0]['Endpoint']['Address'];
//echo $endpoint . "\n";
$link = mysqli_connect($endpoint,"sreddy7","sreddy123","school",3306) or die("Error " . mysqli_error($link));
//$link = mysqli_connect($endpoint,"controller","ilovebunnies","inclass") or die("Error " . mysqli_error($link));
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
// code to insert new record
/* Prepared statement, stage 1: prepare */
if (!($stmt = $link->prepare("INSERT INTO items(email, phone, s3rawurl, s3finishedurl, issubscribed,status,receipt ) VALUES (?,?,?,?,?,?,?)"))) {
    echo "Prepare failed: (" . $stmt->errno . ") " . $stmt->error;
}
$email=$_SESSION['email'];
$phone='3125618112';
$finishedurl=' ';
$status=0;
$issubscribed=0;
$receipt=md5($url);
// prepared statements will not accept literals (pass by reference) in bind_params, you need to declare variables
$stmt->bind_param("ssssiis",$email,$phone,$url,$finishedurl,$issubscribed,$status,$receipt);
if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}
printf("%d Row inserted.<br/>", $stmt->affected_rows);
/* explicit close recommended */
$stmt->close();
// SELECT *
$link->real_query("SELECT * FROM items");
$res = $link->use_result();
echo "Result set order...\n"."<br/>";
while ($row = $res->fetch_assoc()) {
    echo " id = " . $row['id'] . "\n";
}
$link->close();

// PUT MD5 hash of raw URL to SQS QUEUE
$sqsclient = new Aws\Sqs\SqsClient([
    'region'  => 'us-west-2',
    'version' => 'latest'
]);

// Code to retrieve the Queue URLs
$sqsresult = $sqsclient->getQueueUrl([
    'QueueName' => 'sreddy7', // REQUIRED
]);

echo "</br>";

$queueUrl = $sqsresult->get('QueueUrl');
echo "This is the SQS URL: $queueUrl";
echo "</br>";

//echo $sqsresult['QueueURL'];
//$queueUrl = $sqsresult['QueueURL'];

$sqsresult = $sqsclient->sendMessage([
    'MessageBody' => $receipt, // REQUIRED
    'QueueUrl' => $queueUrl // REQUIRED
]);

echo "Message Id:" . $sqsresult['MessageId'];
echo "<br/>"
?>


<html>
<head><title>Uploader</title>
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
<a href="upload.php"> upload </a> 

</body>
</html>
