<?php
require_once 'config.php';
require_once 'class.user.php';

$user = new USER($conn);

if (isset($_POST['logout'])) {
    $user->logout();
}

// Menu links for guests
<<<<<<< HEAD
$menuLinks = array(
    array("title" => "Hem", "url" => "front_page.php"),
    array("title" => "Böcker", "url" => "books.php"),
    array("title" => "Exklusivt", "url" => "exclusives.php"),
    array("title" => "Om oss", "url" => "about-us.php"),
    array("title" => "Logga in", "url" => "login.php")
);

// Menu links for admin
$adminMenuLinks = array(
    array("title" => "Adminpanel", "url" => "admin.php")
);
=======
$menuLinks = [
    ["title" => "Hem", "url" => "front_page.php"],
    ["title" => "Böcker", "url" => "books.php"],
    ["title" => "Exklusivt", "url" => "exclusives.php"],
    ["title" => "Om oss", "url" => "about-us.php"],
    ["title" => "Logga in", "url" => "login.php"],
];
>>>>>>> c53958e3870ae13a87c1da923836ac65b255fb34
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Qvintus - Inloggningssystem</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<header>
    <nav id="navigation">
        <a href="../index.php">
            <img src="images/Qvintus_Logo.png" style="height: 60px; width: 120px;">
        </a>

        <?php if ($user->checkUserLogInStatus()): ?>
            <a href="../home.php" class="button">Hem</a>
<<<<<<< HEAD
            
=======

>>>>>>> c53958e3870ae13a87c1da923836ac65b255fb34
            <?php if ($user->checkUserRole(50)): ?>
                <a href="../admin.php" class="button">Adminpanel</a>
            <?php endif; ?>

<<<<<<< HEAD
            <form method="get" action="" style="display: inline;">
=======
            <form method="post" action="" style="display: inline;">
>>>>>>> c53958e3870ae13a87c1da923836ac65b255fb34
                <input type="submit" value="Logga ut" name="logout" class="button">
            </form>

        <?php else: ?>
            <?php foreach ($menuLinks as $link): ?>
                <a href="../<?= $link['url'] ?>" class="button"><?= $link['title'] ?></a>
            <?php endforeach; ?>
        <?php endif; ?>
    </nav>
</header>
