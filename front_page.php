<?php
include "includes/header.php";
<<<<<<< HEAD
require_once "includes/db.php";
=======
require_once "includes/db.php"; // PDO connection
>>>>>>> c53958e3870ae13a87c1da923836ac65b255fb34
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Boköversikt</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="js/script.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="main-container">

<?php
$search = "";
$results = [];

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $like = "%" . $search . "%";

    $stmt = $conn->prepare("
        SELECT 
            table_books.book_id,
            table_books.book_title AS title,
            table_books.book_description AS description,
            table_books.book_cover AS cover_image,
            table_books.book_rarity AS is_rare,
            t_genres.genre_name
        FROM table_books
        LEFT JOIN t_book_genres ON table_books.book_id = t_book_genres.book_id_fk
        LEFT JOIN t_genres ON t_book_genres.genre_id_fk = t_genres.genre_id
        WHERE (table_books.book_title LIKE :like OR t_genres.genre_name LIKE :like2)
          AND table_books.book_visibility = 1
    ");
    $stmt->bindParam(":like", $like);
    $stmt->bindParam(":like2", $like);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Default: show latest 8 visible books
    $stmt = $conn->prepare("
        SELECT 
            book_id,
            book_title AS title,
            book_description AS description,
            book_cover AS cover_image,
            book_rarity AS is_rare
        FROM table_books
        WHERE book_visibility = 1
        ORDER BY book_release_date DESC
        LIMIT 8
    ");
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!-- Search Form -->
<div class="search-container">
    <h2>Sök böcker</h2>
    <form method="GET" action="front_page.php">
        <input type="text" name="search" placeholder="Enter book title or genre..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Sök</button>
    </form>
</div>

<!-- Book Results -->
<div class="results-container">
    <?php if (!empty($results)): ?>
        <div class="card-grid">
            <?php foreach ($results as $book): ?>
                <div class="card">
                    <?php if (!empty($book['is_rare'])): ?>
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

<!-- Swiper for Rare Books -->
<h3 class="mt-5">Sällsynta böcker</h3>
<div class="swiper">
    <div class="swiper-wrapper">
        <?php
        $stmt = $conn->prepare("
            SELECT book_title AS title, book_description AS description, book_cover AS cover_image
            FROM table_books
            WHERE book_rarity = 1 AND book_visibility = 1
=======
    <!-- 📚 Search Form -->
    <div class="search-container">
        <h2>Sök böcker</h2>
        <form method="GET" action="book_page.php">
            <input type="text" name="search" placeholder="Enter book title or genre...">
            <button type="submit">Sök</button>  
        </form>
    </div>

    <!-- 🏆 Rare Books -->
    <h3 class="mt-5">Sällsynta och värdefulla böcker</h3>
    <div class="card-grid">
        <?php
        $stmt = $conn->prepare("
            SELECT *
            FROM table_books
            WHERE book_rarity = 1 AND status_fk = 1
            ORDER BY book_release_date DESC
c53958e3870ae13a87c1da923836ac65b255fb34
            LIMIT 20
        ");
        $stmt->execute();
        $rare_books = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rare_books as $book):
            $cover = "images/book_" . $book['book_id'] . ".jpg";
            if (!file_exists($cover)) $cover = "images/placeholder.jpg";
        ?>
            <div class="card" data-bs-toggle="modal" data-bs-target="#bookModal<?= $book['book_id'] ?>">
                <div class="rare-badge">RARE</div>
                <img src="<?= $cover ?>" alt="<?= htmlspecialchars($book['book_title']) ?>">
                <div class="card-content">
                    <h4><?= htmlspecialchars($book['book_title']) ?></h4>
                    <p class="desc"><?= htmlspecialchars(mb_strimwidth($book['book_desc'],0,120,'...')) ?></p>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="bookModal<?= $book['book_id'] ?>" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><?= htmlspecialchars($book['book_title']) ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <img src="<?= $cover ?>" class="img-fluid mb-3">
                            <p><strong>Author:</strong> <?= htmlspecialchars($book['book_author']) ?></p>
                            <p><strong>Illustrator:</strong> <?= htmlspecialchars($book['illustrator']) ?></p>
                            <p><strong>Language:</strong> <?= htmlspecialchars($book['book_language']) ?></p>
                            <p><strong>Release Date:</strong> <?= htmlspecialchars($book['book_release_date']) ?></p>
                            <p><strong>Pages:</strong> <?= htmlspecialchars($book['book_pages']) ?></p>
                            <p><strong>Price:</strong> <?= htmlspecialchars($book['book_price']) ?> €</p>
                            <p><strong>Rarity:</strong> <?= $book['book_rarity'] ? 'Rare' : 'Normal' ?></p>
                            <p><strong>Description:</strong><br><?= nl2br(htmlspecialchars($book['book_desc'])) ?></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Stäng</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
</div>

<!-- Swiper for Popular Genres -->
<h3 class="mt-5">Populära genrer</h3>
<div class="swiper">
    <div class="swiper-wrapper">
        <?php
        $stmt = $conn->prepare("SELECT genre_name FROM t_genres WHERE display = 1 LIMIT 20");
=======

    <!-- 🎨 Popular Genres -->
    <h3 class="mt-5">Populära genrer</h3>
    <div class="swiper">
        <div class="swiper-wrapper">
            <?php
            $stmt = $conn->prepare("
                SELECT *
                FROM table_genres
                WHERE is_populare > 0
                ORDER BY is_populare DESC
                LIMIT 20
            ");
            $stmt->execute();
            $popular_genres = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($popular_genres as $genre):
                $cover = "images/genre_" . $genre['genre_id'] . ".jpg";
                if (!file_exists($cover)) $cover = "images/placeholder.jpg";
            ?>
                <div class="swiper-slide card" style="height:180px; width:140px; text-align:center; padding:5px;">
                    <img src="<?= $cover ?>" alt="<?= htmlspecialchars($genre['genre_name']) ?>" style="height:100px; width:auto; object-fit:cover;">
                    <div class="card-content" style="font-size:0.9em; margin-top:5px;">
                        <h5 style="margin:0;"><?= htmlspecialchars($genre['genre_name']) ?></h5>
                        <p style="margin:0;">Popularity: <?= $genre['is_populare'] ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>

    <!-- 🌟 Popular Books -->
    <h3 class="mt-5">Populärt just nu</h3>
    <div class="card-grid">
        <?php
        $stmt = $conn->prepare("
            SELECT *
            FROM table_books
            WHERE status_fk = 1
            ORDER BY book_popularity DESC
            LIMIT 10
        ");
>>>>>>> c53958e3870ae13a87c1da923836ac65b255fb34
        $stmt->execute();
        $popular_books = $stmt->fetchAll(PDO::FETCH_ASSOC);


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

        foreach ($popular_books as $book):
            $cover = "images/book_" . $book['book_id'] . ".jpg";
            if (!file_exists($cover)) $cover = "images/placeholder.jpg";
 c53958e3870ae13a87c1da923836ac65b255fb34
        ?>
            <div class="card" data-bs-toggle="modal" data-bs-target="#bookModal<?= $book['book_id'] ?>">
                <img src="<?= $cover ?>" alt="<?= htmlspecialchars($book['book_title']) ?>">
                <div class="card-content">
                    <h4><?= htmlspecialchars($book['book_title']) ?></h4>
                </div>
            </div>


<!-- Custom Request Section -->
<div class="request-section">
    <h2>Hittar inte det du söker?</h2>
    <p>Inga problem, vi klarar det flesta önskemål, stora som små.</p>
    <a href="recommends.php" class="request-button">Gör ett önskemål</a>
</div>

<!-- 50/50 Split Section -->
<div class="split-section">
    <div class="split-text">
        <h2>Hälsningar från Qvintus!</h2>
        <p>
            Vi erbjuder skräddarsydda bokrekommendationer, sällsynta exemplar och specialbeställningar – allt för att göra din läsupplevelse ännu bättre.
        </p>

            <!-- Modal -->
            <div class="modal fade" id="bookModal<?= $book['book_id'] ?>" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><?= htmlspecialchars($book['book_title']) ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <img src="<?= $cover ?>" class="img-fluid mb-3">
                            <p><strong>Author:</strong> <?= htmlspecialchars($book['book_author']) ?></p>
                            <p><strong>Illustrator:</strong> <?= htmlspecialchars($book['illustrator']) ?></p>
                            <p><strong>Language:</strong> <?= htmlspecialchars($book['book_language']) ?></p>
                            <p><strong>Release Date:</strong> <?= htmlspecialchars($book['book_release_date']) ?></p>
                            <p><strong>Pages:</strong> <?= htmlspecialchars($book['book_pages']) ?></p>
                            <p><strong>Price:</strong> <?= htmlspecialchars($book['book_price']) ?> €</p>
                            <p><strong>Rarity:</strong> <?= $book['book_rarity'] ? 'Rare' : 'Normal' ?></p>
                            <p><strong>Description:</strong><br><?= nl2br(htmlspecialchars($book['book_desc'])) ?></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Stäng</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!--  Custom Request Section -->
    <div class="request-section">
        <h2>Hittar inte det du söker?</h2>
        <p>Inga problem, vi klarar det flesta önskemål, stora som små.</p>
        <a href="recommends.php" class="request-button">Gör ett önskemål</a>
 c53958e3870ae13a87c1da923836ac65b255fb34
    </div>

    <!--  50/50 Split Section -->
    <div class="split-section">
        <div class="split-text">
            <h2>Hälsningar från Qvintus!</h2>
            <p>
                Vi erbjuder skräddarsydda bokrekommendationer, sällsynta exemplar och specialbeställningar – allt för att göra din läsupplevelse ännu bättre.
            </p>
        </div>
        <div class="split-image">
            <img src="images/gubbe" alt="Qvintus">
        </div>
    </div>

</div> <!-- End main-container -->
</body>
</html>

<?php include "includes/footer.php"; ?>
