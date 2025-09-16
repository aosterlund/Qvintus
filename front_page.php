<?php
include "includes/header.php";
require_once "includes/db.php"; // Your PDO connection
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <script src="js/script.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <meta charset="UTF-8">
    <title>Boköversikt</title>
</head>
<body>
<div class="main-container">

<?php
$search = "";
$results = [];

// Search functionality
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $like = "%" . $search . "%";

    $stmt = $conn->prepare("
        SELECT 
            tb.book_id,
            tb.book_title AS title,
            tb.book_desc AS description,
            tb.book_language,
            tb.book_release_date,
            tb.book_pages,
            tb.book_price,
            tb.book_rarity AS book_rarity,
            tg.genre_name
        FROM table_books tb
        LEFT JOIN t_book_genres tbg ON tb.book_id = tbg.book_id_fk
        LEFT JOIN t_genres tg ON tbg.genre_id_fk = tg.genre_id
        WHERE (tb.book_title LIKE :like OR tg.genre_name LIKE :like2)
          AND tb.status_fk = 1
    ");
    $stmt->bindParam(":like", $like);
    $stmt->bindParam(":like2", $like);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

} else {
    // Default: latest 8 visible books
    $stmt = $conn->prepare("
        SELECT 
            book_id,
            book_title AS title,
            book_desc AS description,
            book_language,
            book_release_date,
            book_pages,
            book_price,
            book_rarity AS book_rarity
        FROM table_books
        WHERE status_fk = 1
        ORDER BY book_release_date DESC
        LIMIT 8
    ");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!-- 📚 Search Form -->
<div class="search-container">
    <h2>Sök böcker</h2>
    <form method="GET" action="front_page.php">
        <input type="text" name="search" placeholder="Enter book title or genre..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Sök</button>
    </form>
</div>

<!-- 📋 Book Results -->
<div class="results-container">
    <?php if (!empty($results)): ?>
        <div class="card-grid">
            <?php foreach ($results as $book): ?>
                <div class="card">
                    <?php if (!empty($book['book_rarity'])): ?>
                        <div class="rare-badge">RARE</div>
                    <?php endif; ?>
                    <img src="<?= !empty($book['cover_image']) ? htmlspecialchars($book['cover_image']) : 'images/placeholder.jpg' ?>" alt="<?= htmlspecialchars($book['title']) ?>">
                    <div class="card-content">
                        <h4><?= htmlspecialchars($book['title']) ?></h4>
                        <?php if (!empty($book['genre_name'])): ?>
                            <p class="genre"><?= htmlspecialchars($book['genre_name']) ?></p>
                        <?php endif; ?>
                        <p class="desc"><?= htmlspecialchars(mb_strimwidth($book['description'], 0, 120, '...')) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Inga böcker hittades.</p>
    <?php endif; ?>
</div>

<!-- 🔥 Swiper for Rare Books -->
<h3 class="mt-5">Sällsynta böcker</h3>
<div class="swiper">
    <div class="swiper-wrapper">
        <?php
        $stmt = $conn->prepare("
            SELECT 
                book_id,
                book_title AS title,
                book_desc AS description,
                book_rarity AS book_rarity
            FROM table_books
            WHERE book_rarity = 1 AND status_fk = 1
            LIMIT 20
        ");
        $stmt->execute();
        $rare_books = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($rare_books)) {
            foreach ($rare_books as $book) {
                // Construct image path from the images folder
                // Example: images/book_{id}.jpg
                $cover = "images/book_" . $book['book_id'] . ".jpg";
                
                // If the image file does not exist, use a placeholder
                if (!file_exists($cover)) {
                    $cover = "images/placeholder.jpg";
                }

                $title = htmlspecialchars($book['title']);
                $desc = htmlspecialchars(mb_strimwidth($book['description'], 0, 100, '...'));

                echo '<div class="swiper-slide">';
                echo    '<img src="' . $cover . '" class="card-image" alt="' . $title . '">';
                echo    '<div class="card-text">';
                echo        '<h4>' . $title . '</h4>';
                echo        '<p>' . $desc . '</p>';
                echo    '</div>';
                echo '</div>';
            }
        } else {
            echo '<p>Inga sällsynta böcker hittades.</p>';
        }
        ?>
    </div>

    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
</div>

<!-- 🔥 Swiper for Popular Genres -->
<h3 class="mt-5">Populära genrer</h3>
<div class="swiper">
    <div class="swiper-wrapper">
    <?php
    $stmt = $conn->prepare("
        SELECT genre_name 
        FROM table_genres
        LIMIT 20
    ");

        $stmt->execute();
        $popular_genres = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($popular_genres)) {
            foreach ($popular_genres as $genre) {
                $genre_name = htmlspecialchars($genre['genre_name']);
                echo '<div class="swiper-slide">';
                echo    '<div class="card-text">';
                echo        '<h4>' . $genre_name . '</h4>';
                echo    '</div>';
                echo '</div>';
            }
        } else {
            echo '<p>Inga populära genrer hittades.</p>';
        }
        ?>
    </div>
</div>

<!-- 🧩 Custom Request Section -->
<div class="request-section">
    <h2>Hittar inte det du söker?</h2>
    <p>Inga problem, vi klarar det flesta önskemål, stora som små.</p>
    <a href="recommends.php" class="request-button">Gör ett önskemål</a>
</div>

<!-- 🖼️ 50/50 Split Section -->
<div class="split-section">
    <div class="split-text">
        <h2>Hälsningar från Qvintus!</h2>
        <p>
            Vi erbjuder skräddarsydda bokrekommendationer, sällsynta exemplar och specialbeställningar – allt för att göra din läsupplevelse ännu bättre.
        </p>
    </div>
    <div class="split-image">
        <img src="images/icon.php">
    </div>
</div>

</div> <!-- End main-container -->
</body>
</html>

<?php include "includes/footer.php"; ?>
