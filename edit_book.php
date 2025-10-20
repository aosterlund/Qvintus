<?php
include "includes/header.php";
require_once "includes/db.php"; // PDO connection

$successMessage = '';

// Get the book ID from URL
$book_id = $_GET['id'] ?? null;
if (!$book_id) {
    echo "<div class='alert alert-danger'>Ingen bok vald.</div>";
    exit;
}

// Fetch current book info
$stmt = $conn->prepare("SELECT * FROM table_books WHERE book_id = ?");
$stmt->execute([$book_id]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    echo "<div class='alert alert-danger'>Boken finns inte.</div>";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $book_title        = $_POST['book_title'];
    $book_desc         = $_POST['book_desc'];
    $book_language     = $_POST['book_language'];
    $book_release_date = $_POST['book_release_date'];
    $book_pages        = $_POST['book_pages'];
    $book_price        = $_POST['book_price'];
    $age_recommendation_fk = $_POST['age_recommendation_fk'];
    $publisher_fk      = $_POST['publisher_fk'];
    $created_by_fk     = $_POST['created_by_fk'];
    $status_fk         = $_POST['status_fk'];
    $illustrator       = $_POST['illustrator'];
    $book_author       = $_POST['book_author'];
    $book_rarity       = isset($_POST['book_rarity']) ? 1 : 0;
    $book_popularity   = $_POST['book_popularity'] ?? 0;

    // Handle cover upload
    $book_cover = $book['book_cover']; // Keep existing if not replaced
    if (!empty($_FILES['book_cover']['name'])) {
        $upload_dir = "images/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
        $filename = uniqid("book_") . "_" . basename($_FILES["book_cover"]["name"]);
        $target_file = $upload_dir . $filename;
        if (move_uploaded_file($_FILES["book_cover"]["tmp_name"], $target_file)) {
            $book_cover = $target_file;
        }
    }

    // Update book info in table_books
    $stmt = $conn->prepare("
        UPDATE table_books SET
            book_title = ?, book_desc = ?, book_language = ?, book_release_date = ?,
            book_pages = ?, book_price = ?, age_recommendation_fk = ?, publisher_fk = ?,
            created_by_fk = ?, status_fk = ?, illustrator = ?, book_author = ?,
            book_rarity = ?, book_popularity = ?, book_cover = ?
        WHERE book_id = ?
    ");
    $stmt->execute([
        $book_title, $book_desc, $book_language, $book_release_date,
        $book_pages, $book_price, $age_recommendation_fk, $publisher_fk,
        $created_by_fk, $status_fk, $illustrator, $book_author,
        $book_rarity, $book_popularity, $book_cover, $book_id
    ]);

    $successMessage = " Boken '" . htmlspecialchars($book_title) . "' har uppdaterats!";
    // Refresh book info after update
    $stmt = $conn->prepare("SELECT * FROM table_books WHERE book_id = ?");
    $stmt->execute([$book_id]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<style>
    #successAlert {
        color: green;
        font-weight: bold;
        margin: 15px 0;
        transition: opacity 1s;
    }
</style>

<main class="container py-5">
    <h2 class="mb-4 text-center">Editera bok</h2>

    <?php if ($successMessage): ?>
        <div id="successAlert"><?= $successMessage ?></div>
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

    <form method="POST" enctype="multipart/form-data" class="mx-auto" style="max-width: 700px;">

        <div class="mb-3">
            <label class="form-label">Titel:</label>
            <input type="text" name="book_title" class="form-control" value="<?= htmlspecialchars($book['book_title']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Beskrivning:</label>
            <textarea name="book_desc" class="form-control" rows="4" required><?= htmlspecialchars($book['book_desc']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Språk:</label>
            <input type="text" name="book_language" class="form-control" value="<?= htmlspecialchars($book['book_language']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Utgivningsdatum:</label>
            <input type="date" name="book_release_date" class="form-control" value="<?= $book['book_release_date'] ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Antal sidor:</label>
            <input type="number" name="book_pages" class="form-control" value="<?= $book['book_pages'] ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Pris:</label>
            <input type="number" step="0.01" name="book_price" class="form-control" value="<?= $book['book_price'] ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Åldersrekommendation (FK):</label>
            <input type="number" name="age_recommendation_fk" class="form-control" value="<?= $book['age_recommendation_fk'] ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Förlag (publisher_fk):</label>
            <input type="number" name="publisher_fk" class="form-control" value="<?= $book['publisher_fk'] ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Skapad av (created_by_fk):</label>
            <input type="number" name="created_by_fk" class="form-control" value="<?= $book['created_by_fk'] ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Status (FK):</label>
            <input type="number" name="status_fk" class="form-control" value="<?= $book['status_fk'] ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Illustratör:</label>
            <input type="text" name="illustrator" class="form-control" value="<?= htmlspecialchars($book['illustrator']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Författare:</label>
            <input type="text" name="book_author" class="form-control" value="<?= htmlspecialchars($book['book_author']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Popularitet:</label>
            <input type="number" name="book_popularity" class="form-control" value="<?= $book['book_popularity'] ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Omslagsbild:</label>
            <input type="file" name="book_cover" class="form-control">
            <?php if($book['book_cover']): ?>
                <img src="<?= $book['book_cover'] ?>" alt="Book Cover" style="max-width:100px; margin-top:10px;">
            <?php endif; ?>
        </div>

        <div class="form-check mb-3">
            <input type="checkbox" name="book_rarity" class="form-check-input" id="book_rarity" <?= $book['book_rarity'] ? 'checked' : '' ?>>
            <label class="form-check-label" for="book_rarity">Sällsynt bok</label>
        </div>

        <button type="submit" class="btn btn-primary w-100">Editera bok</button>
    </form>
    <div class="text-center mt-4">
        <a href="manage_books.php" class="btn btn-outline-primary">Tillbaka till Böcker</a>
    </div>
</main>

<?php include "includes/footer.php"; ?>
