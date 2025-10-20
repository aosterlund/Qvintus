<?php
include "includes/header.php";
require_once "includes/db.php"; // Database connection

$successMessage = "";
$errorMessage = "";

// Check if book ID exists in URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $book_id = (int) $_GET['id'];

    try {
        // Delete book from database
        $stmt = $conn->prepare("DELETE FROM table_books WHERE book_id = ?");
        $stmt->execute([$book_id]);

        $successMessage = "✅ Boken har tagits bort från systemet.";
    } catch (PDOException $e) {
        $errorMessage = "❌ Fel vid borttagning av bok: " . htmlspecialchars($e->getMessage());
    }
} else {
    $errorMessage = "Ingen giltig bok har angetts att ta bort.";
}
?>

<!-- ✅ STYLES -->
<style>
    body {
        background-color: #f8f9fa;
    }
    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .btn {
        border-radius: 10px;
        font-weight: 500;
    }
</style>

<main class="container py-5">
    <div class="card mx-auto p-4 text-center" style="max-width: 600px;">
        <h2 class="mb-4">Radera Bok</h2>

        <?php if ($successMessage): ?>
            <div class="alert alert-success"><?= $successMessage ?></div>
            <a href="manage_own_books.php" class="btn btn-outline-primary">⬅ Tillbaka till boklista</a>
        <?php elseif ($errorMessage): ?>
            <div class="alert alert-danger"><?= $errorMessage ?></div>
            <a href="book_list.php" class="btn btn-secondary mt-3">⬅ Tillbaka</a>
        <?php else: ?>
            <div class="alert alert-warning">Ingen bok angiven att ta bort.</div>
            <a href="book_list.php" class="btn btn-secondary mt-3">⬅ Tillbaka</a>
        <?php endif; ?>
    </div>
</main>

<?php include "includes/footer.php"; ?>
