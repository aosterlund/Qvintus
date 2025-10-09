<?php
include "includes/header.php";

// Handle login if posted
if (isset($_POST['login'])) {
    $logInReturn = $user->login();
    if ($logInReturn == "Success!") {
        $user->redirect("home.php");
    } else {
        echo $logInReturn;
    }
}

// Database connection setup (adjust if config used)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "2025_login_system_sen";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$search = "";
$results = [];

if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $like = "%" . $search . "%";

    $stmt = $conn->prepare("
        SELECT 
            t_books.book_id,
            t_books.title,
            t_books.description,
            t_books.cover_image,
            t_genres.genre_name
        FROM t_books
        LEFT JOIN t_book_genres ON t_books.book_id = t_book_genres.book_id_fk
        LEFT JOIN t_genres ON t_book_genres.genre_id_fk = t_genres.genre_id
        WHERE (t_books.title LIKE ? OR t_genres.genre_name LIKE ?)
        AND t_books.visibility = 1
    ");
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $results = $stmt->get_result();
}
?>

<!-- 📚 Search Form -->
<div class="search-container">
    <h2>Sök böcker</h2>
    <form method="GET" action="search_page.php">
        <input type="text" name="search" placeholder="Enter book title or genre..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Sök</button>
    </form>
</div>

<!-- 📋 Search Results as Cards -->
<?php if (!empty($search)): ?>
    <div class="results-container">
        <h3>Resultat för "<?= htmlspecialchars($search) ?>"</h3>

        <?php if ($results && $results->num_rows > 0): ?>
            <div class="card-grid">
                <?php while ($book = $results->fetch_assoc()): ?>
                    <div class="card">
                        <img src="<?= !empty($book['cover_image']) ? htmlspecialchars($book['cover_image']) : 'images/placeholder.jpg' ?>" alt="<?= htmlspecialchars($book['title']) ?>">
                        <div class="card-content">
                            <h4><?= htmlspecialchars($book['title']) ?></h4>
                            <p class="genre"><?= htmlspecialchars($book['genre_name'] ?? 'Unknown Genre') ?></p>
                            <p class="desc"><?= htmlspecialchars(mb_strimwidth($book['description'], 0, 120, '...')) ?></p>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>Inga böcker hittad.</p>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php
include "includes/footer.php";
$conn->close();
?>
