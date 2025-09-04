<?php
    include "includes/header.php";

    if (isset($_POST['login'])) {
        $logInReturn = $user->login();
        if ($logInReturn == "Success!") {
           $user->redirect("home.php");
        }

        else {
            echo $logInReturn;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="css/style.css">
    <script src="js/script.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logga In</title>
</head>
<body>
<div class="text-center">
    <form method="post"id="loginform">
        <label for="username">Username</label><br>
        <input type="text" id="username" name="username"><br><br>

        <label for="password">Password</label><br>
        <input type="password" id="password" name="password"><br><br>


        <input class="button" type="submit" name="login" id="loginbtn" value="Log in"><br><br>


        <a class="loginpagelink" href="#">Forgot password?</a>
        <a class="loginpagelink" href="register.php">Register</a>

    </form>
<div>
</body>
</html>

<?php
    include "includes/footer.php"
?>