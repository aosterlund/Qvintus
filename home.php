<?php
    include "includes/header.php";

    if (!$user->checkUserLogInStatus()) {
        $user->redirect("index.php");
    }

    if (isset($_POST['logout_button'])) {
        $user->logout();
    }

    echo "<h1>Welcome ".$_SESSION['user_name'].".</h1>";
    echo "<p>Your ID is ".$_SESSION['user_id']." and your role is ".$_SESSION['user_role'].".</p>"
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
    <title>Homepage</title>
</head>
<body>
    


</body>
</html>

<?php
    include "includes/footer.php";
?>