<?php
	$servername = "127.0.0.1";
	$username = "root";
	$password = "";

	try {
	  $conn = new PDO("mysql:host=$servername;dbname=2024_login_system_sen;charset=utf8mb4", $username, $password);
	  // set the PDO error mode to exception
	  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	  echo "Connected successfully<br>";
	} catch(PDOException $e) {
	  echo "Connection failed: " . $e->getMessage();
	}
	
	if (!isset($_SESSION)) {
        session_start();
    }

    $user = new USER($conn);

?>