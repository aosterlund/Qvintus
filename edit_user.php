<?php
include "includes/header.php";
require_once "includes/db.php";

if (!isset($_GET['u_id'])) {
    header("Location: user_list.php");
    exit;
}

$u_id = intval($_GET['u_id']);
$successMessage = '';

try {
    // Fetch user info
    $stmt = $conn->prepare("SELECT * FROM table_users WHERE u_id = ?");
    $stmt->execute([$u_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "<div class='alert alert-danger'>Användare hittades inte.</div>";
        exit;
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $u_name = $_POST['u_name'];
        $u_email = $_POST['u_email'];
        $u_role_fk = $_POST['u_role_fk'];
        $u_status = $_POST['u_status'];
        $u_password = $_POST['u_password'];

        // Hash password only if changed
        $password_sql = '';
        $params = [$u_name, $u_email, $u_role_fk, $u_status, $u_id];
        if (!empty($u_password)) {
            $hashed_password = password_hash($u_password, PASSWORD_DEFAULT);
            $password_sql = ", u_password = ?";
            array_splice($params, 4, 0, $hashed_password); // insert hashed password
        }

        $stmt = $conn->prepare("UPDATE table_users SET u_name = ?, u_email = ?, u_role_fk = ?, u_status = ? $password_sql WHERE u_id = ?");
        $stmt->execute($params);

        $successMessage = "✅ Användaren har uppdaterats!";
        
        // Refresh user data
        $stmt = $conn->prepare("SELECT * FROM table_users WHERE u_id = ?");
        $stmt->execute([$u_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    }

} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Fel: " . htmlspecialchars($e->getMessage()) . "</div>";
}
?>

<main class="container py-5">
    <h2 class="mb-4 text-center">Redigera Användare</h2>

    <?php if ($successMessage): ?>
        <div class="alert alert-success text-center"><?= $successMessage ?></div>
    <?php endif; ?>

    <form method="POST" class="mx-auto" style="max-width: 600px;">
        <div class="mb-3">
            <label class="form-label">Namn</label>
            <input type="text" name="u_name" class="form-control" value="<?= htmlspecialchars($user['u_name']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">E-post</label>
            <input type="email" name="u_email" class="form-control" value="<?= htmlspecialchars($user['u_email']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Lösenord (fyll endast om du vill byta)</label>
            <input type="password" name="u_password" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Roll-ID</label>
            <input type="number" name="u_role_fk" class="form-control" value="<?= htmlspecialchars($user['u_role_fk']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Status</label>
            <input type="number" name="u_status" class="form-control" value="<?= htmlspecialchars($user['u_status']) ?>" required>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Uppdatera</button>
            <a href="user_list.php" class="btn btn-secondary">Tillbaka</a>
        </div>
    </form>
</main>

<?php include "includes/footer.php"; ?>
