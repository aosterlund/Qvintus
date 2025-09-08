<?php
require_once 'includes/config.php';
require_once 'includes/class.user.php';
require_once 'includes/class.book.php';
require_once 'includes/config.php';
$user = new User($pdo);
$book = new Book($pdo);

if(isset($_GET['logout'])) {
	$user->logout();
}

$menuLinks = array(
    array(
        "title" => "Hem",
        "url" => "index.php"
		),
		array(
				"title" => "Böcker",
				"url" => "books.php"
		),
		array(
				"title" => "Exklusivt",
				"url" => "exclusives.php"
		),
		array(
				"title" => "Om oss",
				"url" => "about-us.php"
		),
		array(
				"title" => "Logga in",
				"url" => "login.php"
		)
	);
$adminMenuLinks = array(
    array(
        "title" => "Admin panel",
        "url" => "admin.php"
		)
);
?>


<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="css/style.css">
    <script src="js/script.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In System</title>
</head>
<body>
<?php
if ($user->checkUserLogInStatus()) {
?>

<div id="navigation">
	<a href="home.php" class="button">Home</a>
	<?php
	if ($user->checkUserRole(50)) {
		echo "<a href='admin.php' class='button'>Admin</a>";
	}
	?>
	<br>
	<br>
	<form method="post" action="">
		<input type="submit" value="Log out" name="logout" class="button">
	</form>
</div>
<?php }?>
</body>