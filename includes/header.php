<?php
require_once 'config.php';
require_once 'class.user.php';

$user = new USER($conn);

$menuLinks = array(
    array("title" => "Hem", "url" => "home.php"),
    array("title" => "Böcker", "url" => "search_page.php"),
    array("title" => "Exklusivt", "url" => "exclusives.php"),
    array("title" => "Om oss", "url" => "about_us.php"),
    array("title" => "Logga in", "url" => "login.php")
);

if (isset($_GET['logout'])) {
    $user->logout();
}
?>

<!--<a href="front_page.php" class="button">Hem</a>
<a href="books.php" class="button">Böcker</a>
<a href="exclusives.php" class="button">Exklusivt</a>
<a href="about-us.php" class="button">Om oss</a>
<a href="login.php" class="button">Logga in</a>-->
<?php
$adminMenuLinks = array(
    array("title" => "Adminpanel", "url" => "admin.php")
);
?>
<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Qvintus - Inloggningssystem</title>
    <link rel="stylesheet" href="../css/style.css">
    <!-- other head stuff -->
</head>
<body>
<header>
    <nav id="navigation">
        <a href="../index.php"> <!-- adjust path if needed -->
           <img src="images/Qvintus_Logo.png" style="height: 60px; width: 120px;">
        </a>
        <?php if ($user->checkUserLogInStatus()): ?>
            <a href="../home.php" class="button">Hem</a>
            <?php if ($user->checkUserRole(50)): ?>
                <a href="../admin.php" class="button">Adminpanel</a>
            <?php endif; ?>
            <form method="post" action="" style="display: inline;">
                <input type="submit" value="Logga ut" name="logout" class="button">
            </form>
        <?php else: ?>
            <?php foreach ($menuLinks as $link): ?>
                <a href="<?= $link['url'] ?>" class="button"><?= $link['title'] ?></a>
            <?php endforeach; ?>
        <?php endif; ?>
    </nav>

</header>
