<?php
  // include "includes/header.php";
  require_once "includes/db.php"; // Your PDO connection
  include "includes/config.php";
  include "includes/header.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
<link  href="css/style.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <script src="js/script.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>framsida</title>
</head>
<body>


 <div class="main-container">

    <!-- 📚 Search Form -->
    <!--<div class="search-container">
        <h2>Sök böcker</h2>
        <form method="GET" action="book_page.php">
            <input type="text" name="search" placeholder="Enter book title or genre...">
            <button type="submit">Sök</button>  
        </form>
    </div>-->

    <!--line across sideways-->
    <div class="on-line" style="width:auto; height:1px; background-color:black;"></div>


    <!-- 🏆 Rare Books -->
    <h3 class="mt-5">Sällsynta och värdefulla böcker</h3>
    <div class="card-grid">
        <?php
        $stmt = $conn->prepare("
            SELECT *
            FROM table_books
            WHERE book_rarity = 1 AND status_fk = 1
            ORDER BY book_release_date DESC
            LIMIT 20
        ");
        $stmt->execute();
        $rare_books = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rare_books as $book):
            $cover = "images/book_" . $book['book_id'] . ".jpg";
            if (!file_exists(__DIR__ . '/' . $cover)) $cover = "images/placeholder.jpg";
        ?>
            <div class="card book-card" data-bs-toggle="modal" data-bs-target="#bookModal<?= $book['book_id'] ?>">
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
        <div class="hubert float-end" ><a href="search_page.php" style="text-decoration:none;"><btn type="button" class="humberto">Se mera</btn></a></div>
    </div>

        <!--line across sideways-->
    <div class="on-line" style="width:auto; height:1px; background-color:black;"></div>

    <!-- 🎨 Popular Genres -->
    <h3 class="mt-5">Populära genrer</h3>
    <div class="swiper">
        <div class="swiper-wrapper">
            <?php
            $stmt = $conn->prepare("
                SELECT *
                FROM table_genres
                WHERE is_popular > 0
                ORDER BY is_popular DESC
                LIMIT 20
            ");
            $stmt->execute();
            $popular_genres = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($popular_genres as $genre):
                $cover = "images/genre_" . $genre['genre_id'] . ".jpg";
                if (!file_exists(__DIR__ . '/' . $cover)) $cover = "images/placeholder.jpg";
            ?>
                <div class="swiper-slide card" style="height:180px; width:140px; text-align:center; padding:5px;">
                    <img src="<?= $cover ?>" alt="<?= htmlspecialchars($genre['genre_name']) ?>" style="height:100px; width:auto; object-fit:cover;">
                    <div class="card-content" style="font-size:0.9em; margin-top:5px;">
                        <h5 style="margin:0;"><?= htmlspecialchars($genre['genre_name']) ?></h5>
                        <!--<p style="margin:0;">Popularity: <?= $genre['is_popular'] ?></p>-->
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="hubert float-end" ><a href="search_page.php" style="text-decoration:none;"><btn type="button" class="humberto">Se mera</btn></a></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div><br>

        
    </div>
                
                <!--line across sideways-->
    <div class="on-line" style="width:auto; height:1px; background-color:black;"></div>

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
        $stmt->execute();
        $popular_books = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($popular_books as $book):
            $cover = "images/book_" . $book['book_id'] . ".jpg";
            if (!file_exists(__DIR__ . '/' . $cover)) $cover = "images/placeholder.jpg";
        ?>
            <div class="card" data-bs-toggle="modal" data-bs-target="#bookModal<?= $book['book_id'] ?>">
                <img src="<?= $cover ?>" alt="<?= htmlspecialchars($book['book_title']) ?>">
                <div class="card-content">
                    <h4><?= htmlspecialchars($book['book_title']) ?></h4>
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

            <!--line across sideways-->
    <div class="on-line" style="width:auto; height:1px; background-color:black;"></div>

    <!-- 🧩 Custom Request Section -->
    <div class="request-section">
        <h2>Hittar inte det du söker?</h2>
        <p>Inga problem, vi klarar det flesta önskemål, stora som små.</p>
        <a href="recommends.php" class="request-button">Gör ett önskemål</a>
    </div>

            <!--line across sideways-->
    <div class="on-line" style="width:auto; height:1px; background-color:black;"></div>

    <!-- 🖼️ 50/50 Split Section -->
    <div class="split-section border border-2">
        <div class="split-text">
            <h2>Hälsningar från Qvintus!</h2>
            <p>
                Vi erbjuder skräddarsydda bokrekommendationer, sällsynta exemplar och specialbeställningar – allt för att göra din läsupplevelse ännu bättre.
            </p>
        </div>
        <div class="split-image">
            <img src="images/ChristmasTree.jpg" alt="Qvintus" class="img-fluid">
        </div>
    </div>




</div> <!-- End main-container -->
</body>
</html>

<?php include "includes/footer.php"; ?>
