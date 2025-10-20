<?php
include "includes/header.php";
require_once "includes/db.php"; // PDO connection

$deletedMessage = '';
if (isset($_GET['deleted']) && $_GET['deleted'] == 1) {
    $deletedMessage = "✅ Användaren har tagits bort!";
}

try {
    $stmt = $conn->query("SELECT * FROM table_users ORDER BY u_id ASC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "<div class='alert alert-danger text-center mt-3'>
            Fel vid hämtning av användare: " . htmlspecialchars($e->getMessage()) . "
          </div>";
}
?>

<style>
    #deletedAlert {
        color: green;
        font-weight: bold;
        margin: 15px 0;
        text-align: center;
        transition: opacity 1s ease-out;
    }
</style>

<main class="container py-5">
    <h2 class="mb-4 text-center">Användarlista</h2>

    <!-- Deleted Alert -->
    <?php if ($deletedMessage): ?>
        <div id="deletedAlert"><?= $deletedMessage ?></div>
        <script>
            setTimeout(() => {
                const alert = document.getElementById('deletedAlert');
                if(alert){
                    alert.style.opacity = 0;
                    setTimeout(() => alert.remove(), 1000);
                }
            }, 5000);
        </script>
    <?php endif; ?>

    <!-- Add User Button -->
    <div class="text-center mb-4">
        <a href="add_user.php" class="btn btn-success btn-lg">Lägg till användare</a>
    </div>

    <?php if (!empty($users)): ?>
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-striped table-hover align-middle text-center">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Namn</th>
                        <th scope="col">E-post</th>
                        <th scope="col">Roll-ID</th>
                        <th scope="col">Åtgärder</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?= htmlspecialchars($user['u_id']) ?></td>
                            <td><?= htmlspecialchars($user['u_name']) ?></td>
                            <td><?= htmlspecialchars($user['u_email']) ?></td>
                            <td><?= htmlspecialchars($user['u_role_fk']) ?></td>
                            <td>
                                <a href="edit_user.php?u_id=<?= $user['u_id'] ?>" class="btn btn-sm btn-primary mb-1">Editera</a>
                                <a href="delete_user.php?u_id=<?= $user['u_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Är du säker på att du vill ta bort denna användare?')">Radera</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-center text-muted">Inga användare hittades i databasen.</p>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="book_panel.php" class="btn btn-outline-primary">Tillbaka till Bokpanel</a>
    </div>
</main>

<?php include "includes/footer.php"; ?>
