<?php
include "includes/header.php";
require_once "includes/db.php"; // PDO connection

// For example, assume the logged-in user ID is stored in session
session_start();
$user_id = $_SESSION['user_id'] ?? 1; // default to 1 if not set

try {
    $stmt = $conn->prepare("SELECT * FROM table_books WHERE created_by_fk = ? ORDER BY book_id ASC");
    $stmt->execute([$user_id]);
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Fel vid hämtning av böcker: " . htmlspecialchars($e->getMessage()) . "</div>";
}
?>

<main class="container py-5">
    <h2 class="mb-4 text-center">Mina Böcker</h2>

        <?php if ($successMessage): ?>
        <div id="successAlert"><?= $successMessage ?></div>
        <script>
            // Fade out after 5 seconds
            setTimeout(() => {
                const alert = document.getElementById('successAlert');
                if(alert){
                    alert.style.opacity = 0;
                    setTimeout(() => alert.remove(), 1000);
                }
            }, 5000);
        </script>
    <?php endif; ?>


    <?php if(empty($books)): ?>
        <p class="text-center">Du har inga böcker registrerade.</p>
    <?php else: ?>
        <div class="table-responsive shadow-sm rounded">
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
                        <th>Sällsynt</th>
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
                            <a href="edit_book.php?id=<?= $book['book_id'] ?>" class="btn btn-secondary btn-sm mb-1">Editera</a>
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
        <a href="user_panel.php" class="btn btn-outline-primary">Tillbaka till Panel</a>
    </div>
</main>

<?php include "includes/footer.php"; ?>
