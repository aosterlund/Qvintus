<?php
require_once "includes/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form inputs
    $title = $_POST['title'];
    $author = $_POST['author'] ?? 'Okänd';
    $language = $_POST['language'] ?? 'Svenska';
    $release_date = $_POST['release_date'];
    $pages = $_POST['pages'];
    $price = $_POST['price'];
    $user_id_fk = $_POST['user_id_fk'] ?? 1;
    $is_rare = isset($_POST['is_rare']) ? 1 : 0;

    // Upload cover image
    $cover_image = null;
    if (!empty($_FILES['cover_image']['name'])) {
        $upload_dir = "uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $filename = uniqid() . "_" . basename($_FILES["cover_image"]["name"]);
        $target_file = $upload_dir . $filename;

        if (move_uploaded_file($_FILES["cover_image"]["tmp_name"], $target_file)) {
            $cover_image = $target_file;
        }
    }

    // Insert into table_books
    $stmt = $conn->prepare("
        INSERT INTO table_books 
        (book_title, book_author, book_language, book_release_date, book_pages, book_price, book_rarity, book_cover, created_by_fk)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $title,
        $author,
        $language,
        $release_date,
        $pages,
        $price,
        $is_rare,
        $cover_image,
        $user_id_fk
    ]);

    echo "<p class='success-message'>Boken har lagts till i databasen!</p>";
}
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Lägg till ny bok</title>
    <link rel="stylesheet" href="css/add_book.css">
</head>
<body>
<div class="add-book-container">
    <h2>Lägg till en ny bok</h2>

    <form method="POST" enctype="multipart/form-data">
        <label>Titel:</label>
        <input type="text" name="title" required>

        <label>Författare:</label>
        <input type="text" name="author">

        <label>Språk:</label>
        <input type="text" name="language">

        <label>Publiceringsdatum:</label>
        <input type="date" name="release_date" required>

        <label>Antal sidor:</label>
        <input type="number" name="pages" required>

        <label>Pris (kr):</label>
        <input type="number" step="0.01" name="price" required>

        <label>Omslagsbild:</label>
        <input type="file" name="cover_image">

        <label>Användar-ID:</label>
        <input type="number" name="user_id_fk" value="1">

        <label>
            <input type="checkbox" name="is_rare">
            Sällsynt bok
        </label>

        <button type="submit">Lägg till bok</button>
    </form>
</div>
</body>
</html>
