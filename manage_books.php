<?php
include "includes/header.php";
require_once "includes/db.php"; // PDO connection

// Fetch all books
$stmt = $conn->query("SELECT * FROM table_books ORDER BY book_id ASC");
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main class="container py-5">
    <h2 class="mb-4 text-center">Hantera Böcker</h2>

    <?php if(empty($books)): ?>
        <p>Inga böcker finns i databasen.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Titel</th>
                        <th>Författare</th>
                        <th>Språk</th>
                        <th>Utgivningsdatum</th>
                        <th>Sidor</th>
                        <th>Pris</th>
                        <th>Rarity</th>
                        <th>Omslag</th>
                        <th>Åtgärder</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($books as $book): ?>
                    <tr>
                        <td><?= htmlspecialchars($book['book_id']) ?></td>
                        <td><?= htmlspecialchars($book['book_title']) ?></td>
                        <td><?= htmlspecialchars($book['book_author']) ?></td>
                        <td><?= htmlspecialchars($book['book_language']) ?></td>
                        <td><?= htmlspecialchars($book['book_release_date']) ?></td>
                        <td><?= htmlspecialchars($book['book_pages']) ?></td>
                        <td><?= htmlspecialchars($book['book_price']) ?> kr</td>
                        <td><?= $book['book_rarity'] ? 'Ja' : 'Nej' ?></td>
                        <td>
                            <?php if($book['book_cover']): ?>
                                <img src="<?= htmlspecialchars($book['book_cover']) ?>" alt="Omslag" style="width:50px; height:auto;">
                            <?php else: ?>
                                Ingen
                            <?php endif; ?>
                        </td>
                        <td>
                            <!-- Editera button -->
                            <a href="edit_book.php?id=<?= $book['book_id'] ?>" class="btn btn-secondary btn-sm mb-1">Editera</a>

                            <!-- Ta bort button -->
                            <a href="delete_book.php?id=<?= $book['book_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Är du säker på att du vill ta bort denna bok?')">Ta bort</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="add_book.php" class="btn btn-primary">Lägg till ny bok</a>
        <a href="book_panel.php" class="btn btn-outline-primary">Tillbaka till Bokpanel</a>
    </div>
</main>

<?php include "includes/footer.php"; ?>
