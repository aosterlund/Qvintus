<?php
// Start session if not already started (needed for success message)
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Include database and header
require_once "includes/db.php";
include "includes/header.php";

// Hardcode user ID for Mod
$user_id = 2;

// Fetch books created by Mod
try {
    $stmt = $conn->prepare("SELECT * FROM table_books WHERE created_by_fk = ? ORDER BY book_id ASC");
    $stmt->execute([$user_id]);
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Fel vid hämtning av böcker: " . htmlspecialchars($e->getMessage()) . "</div>";
    $books = [];
}

// Get success message from session
$successMessage = $_SESSION['successMessage'] ?? null;
unset($_SESSION['successMessage']);
?>

<main class="container py-5">
    <h2 class="mb-4 text-center">Böcker skapade av Mod</h2>

    <!-- Success message -->
    <?php if (!empty($successMessage)): ?>
        <div id="successAlert" class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
        <script>
            setTimeout(() => {
                const alert = document.getElementById('successAlert');
                if(alert){
                    alert.style.opacity = 0;
                    setTimeout(() => alert.remove(), 1000);
                }
            }, 5000);
        </script>
    <?php endif; ?>

    <!-- Books table -->
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
                <?php if (!empty($books)): ?>
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
                <?php else: ?>
                    <tr>
                        <td colspan="10" class="text-center">Mod har inga böcker registrerade.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="text-center mt-4">
        <a href="add_book.php" class="btn btn-primary">Lägg till ny bok</a>
        <a href="user_panel.php" class="btn btn-outline-primary">Tillbaka till Panel</a>
    </div>
</main>

<?php include "includes/footer.php"; ?>
