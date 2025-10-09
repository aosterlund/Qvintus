<?php
session_start();
require_once "includes/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    die("Ogiltig bok-ID.");
}

$book_id = (int)$_GET['id'];

try {
    // Only delete if the book belongs to the logged-in user
    $stmt = $conn->prepare("DELETE FROM table_books WHERE book_id = ? AND created_by_fk = ?");
    $stmt->execute([$book_id, $user_id]);

    if ($stmt->rowCount() === 0) {
        die("Du har inte behörighet att ta bort denna bok, eller boken finns inte.");
    }

    header("Location: manage_own_books.php?success=1");
    exit;
} catch (PDOException $e) {
    echo "Fel vid borttagning: " . htmlspecialchars($e->getMessage());
}
?>
