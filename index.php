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
$link = mysqli_connect($endpoint,"sreddy7","sreddy123","school",3306) or die("Error " . mysqli_error($link));
if (mysqli_connect_errno()) {
  printf("Connect failed: %s\n", mysqli_connect_error());
  exit();
}
$sql = "drop table loginData";
if ($link->query($sql) === TRUE) {
 // echo "Dropping any existing table present"."<br/>";
} else {
 // echo "No table exists to drop"."<br/>";
}
$create_table = 'CREATE TABLE IF NOT EXISTS loginData
(
  name VARCHAR(25) NOT NULL,
  password VARCHAR(25) NOT NULL,
  PRIMARY KEY(name)
  )';
$create_tbl = $link->query($create_table);
if ($create_table) {
 // echo "<b>Creating new table</b>"."<br/>";
 // echo "<b>Table is created successfully</b>"."<br/>";
 // echo "</br>";}
else {
  //echo "Table creation error";
}
//Insert data
$sql = "INSERT INTO loginData (name, password)
VALUES ('sreddy7@hawk.iit.edu', 'password'), ('jhajek@iit.edu','password')";
if ($link->query($sql) === TRUE) {
  //echo "Records inserted successfully"."<br/>";
} else {
  //echo "Error: " . $sql . "<br>" . $link->error;
}

//$link->close();
?>

<?php

session_start();

if (isset($_GET['logout'])) {
	session_destroy();
	//unset($_SESSION['user']);
	header('Location: welcome.php', TRUE, 302);
	exit;
}

if(isset($_SESSION['user']))
{
	header("Location: welcome.php", TRUE, 302);
	exit;
}

function validate($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


	// Shorten Request Variables if they are set
$username = isset($_POST['username']) ? validate($_POST['username']) : '';
$password = isset($_POST['password']) ? validate($_POST['password']) : '';

$formIsValid = true;
$userNameErr = '';
$passwordErr = '';

if(empty($username)){
	$formIsValid = false;
	$userNameErr = "Username is required !!";
}

if(empty($password)){
	$formIsValid = false;
	$passwordErr = "Password is required !!";
}


$valid_user = 'Sindhu';
$valid_hash = '$2y$10$U/VDX9TnloNzBCqw3UoBMOVqkvpGbUPFiGpxFneR6RtRY1kyGQ1We';







(strtolower($username) == strtolower($valid_user) && password_verify($password, $valid_hash))

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

	<?php if($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
	<?php if ($formIsValid) : ?>
	<?php if ((strtolower($username) == strtolower($valid_user)) && (password_verify($password, $valid_hash))): ?>
	<?php	$_SESSION['user'] = $valid_user;
		header("Location: welcome.php", TRUE, 302);
		exit;
	?>
	<?php else: ?>

		<div class="container">
			<div class="jumbotron">

				<h1>Login Page</h1><br>
				<span style="color:red"> <?php print "Username and Password are incorrect"; ?></span><br><br>	
				<form action="index.php" method="POST">
					<label>Username: <input type="text" name="username" value="<?php print $username; ?>" size="40"></label><br><br>
					<label>Password: <input type="password" name="password" value="<?php print $password; ?>" size="40"></label><br><br>
					<input type="submit" class="btn btn-primary" value="Login">
				</form>	
			</div>
		</div>
	<?php endif; ?>	
<?php else: ?>
	<div class="container">
		<div class="jumbotron">

			<h1>Login Page</h1><br>
			<form action="index.php" method="POST">
				<label>Username: <input type="text" name="username" value="<?php print $username; ?>" size="40"></label>
				<span style="color:red"><?php print $userNameErr; ?> </span><br><br>					
				<label>Password: <input type="password" name="password" value="<?php print $password; ?>" size="40"></label>
				<span style="color:red"><?php print $passwordErr; ?> </span><br><br>
				<input type="submit" class="btn btn-primary" value="Login">
			</form>
		</div>
	</div>
<?php endif; ?>
<?php else: ?>
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
<?php endif; ?>
</body>
</html>