<?php
include "includes/header.php";
require_once "includes/db.php"; // PDO connection

// ✅ Hämta sökterm
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$results = [];

if (!empty($search)) {
    $stmt = $conn->prepare("
        SELECT b.book_id, b.book_title, b.book_desc, 
               b.book_rarity, b.book_author, b.illustrator, 
               b.book_language, b.book_release_date, b.book_pages, b.book_price,
               g.genre_name
        FROM table_books b
        LEFT JOIN table_genres g ON b.genre_fk = g.genre_id
        WHERE (b.book_title LIKE :search OR g.genre_name LIKE :search) 
          AND b.status_fk = 1
        ORDER BY b.book_release_date DESC
        LIMIT 50
    ");
    $stmt->execute([':search' => "%$search%"]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Sökresultat – <?= htmlspecialchars($search) ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="main-container">

    <h2>Sökresultat för: "<?= htmlspecialchars($search) ?>"</h2>

    <?php if (!empty($results)): ?>
        <div class="card-grid">
            <?php foreach ($results as $book): ?>
                <?php 
                    // Bygg bildväg från book_id
                    $cover = "images/book_" . $book['book_id'] . ".jpg";
                    if (!file_exists($cover)) $cover = "images/placeholder.jpg";
                ?>
                <div class="card" data-bs-toggle="modal" data-bs-target="#bookModal<?= $book['book_id'] ?>">
                    <?php if (!empty($book['book_rarity'])): ?>
                        <div class="rare-badge">RARE</div>
                    <?php endif; ?>
                    <img src="<?= $cover ?>" alt="<?= htmlspecialchars($book['book_title']) ?>">
                    <div class="card-content">
                        <h4><?= htmlspecialchars($book['book_title']) ?></h4>
                        <?php if (!empty($book['genre_name'])): ?>
                            <p class="genre"><?= htmlspecialchars($book['genre_name']) ?></p>
                        <?php endif; ?>
                        <p class="desc"><?= htmlspecialchars(mb_strimwidth($book['book_desc'], 0, 120, '...')) ?></p>
                    </div>
                </div>

                <!-- Modal med mer info -->
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
                                <p><strong>Price:</strong> <?= htmlspecialchars($book['book_price']) ?> SEK</p>
                                <p><strong>Rarity:</strong> <?= $book['book_rarity'] ? 'Rare' : 'Normal' ?></p>
                                <p><strong>Description:</strong><br><?= nl2br(htmlspecialchars($book['book_desc'])) ?></p>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Inga böcker hittades för din sökning.</p>
    <?php endif; ?>

    <a href="front_page.php" class="btn btn-secondary mt-4">⬅ Tillbaka</a>
</div>
</body>
</html>

<?php include "includes/footer.php"; ?>
