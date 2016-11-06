<?php

require 'vendor/autoload.php';

$s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'region'  => 'us-west-2'
]);

$delresult = $s3->deleteObject(array(
        'Bucket' => 'raw-svr',
        'Key'    => 'switchonarex.png'
));


try {
    // Upload data.
    $result = $s3->putObject(array(
        'Bucket' => 'raw-svr',
        'Key'    => 'switchonarex.png',
        'SourceFile'=>'/var/www/html/switchonarex.png',
        'ACL'    => 'public-read'
    ));

    // Print the URL to the object.
    echo $result['ObjectURL'] . "\n";
} catch (S3Exception $e) {
    echo $e->getMessage() . "\n";
}

?>
<html>
<head>
<title>Testing S3</title>
</head>
<body>
<a href="<?php echo $result['ObjectURL'] ?>">Click here to open in new page</a>
<?php echo "<br/>"."</br>"; ?>
 <img src="<?php echo $result['ObjectURL'] ?>" width="500" height="500" >


</body>
</html>
