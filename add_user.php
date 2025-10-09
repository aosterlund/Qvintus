<?php
include "includes/header.php";
require_once "includes/db.php"; // PDO connection

$successMessage = "";
$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $u_name     = trim($_POST["u_name"]);
    $u_email    = trim($_POST["u_email"]);
    $u_password = $_POST["u_password"];
    $u_role_fk  = $_POST["u_role_fk"];
    $u_status   = $_POST["u_status"];

    if (empty($u_name) || empty($u_email) || empty($u_password)) {
        $errorMessage = "Alla obligatoriska fält måste fyllas i.";
    } else {
        try {
            // Hash password before saving
            $hashedPassword = password_hash($u_password, PASSWORD_BCRYPT);

            // ✅ Let database auto-assign u_id
            $stmt = $conn->prepare("
                INSERT INTO table_users (u_name, u_password, u_email, u_role_fk, u_status)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$u_name, $hashedPassword, $u_email, $u_role_fk, $u_status]);

            $successMessage = "✅ Användaren '" . htmlspecialchars($u_name) . "' har lagts till!";
        } catch (PDOException $e) {
            $errorMessage = "Fel vid tilläggning av användare: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>

<style>
    #successAlert, #errorAlert {
        font-weight: bold;
        margin: 15px 0;
        transition: opacity 1s ease-out;
    }
    #successAlert { color: green; }
    #errorAlert { color: red; }
</style>

<main class="container py-5">
    <h2 class="mb-4 text-center">Lägg till ny användare</h2>

    <?php if ($successMessage): ?>
        <div id="successAlert" class="text-center"><?= $successMessage ?></div>
        <script>
            setTimeout(() => {
                const alert = document.getElementById('successAlert');
                if(alert){
                    alert.style.opacity = 0;
                    setTimeout(() => alert.remove(), 1000);
                }
            }, 4000);
        </script>
    <?php endif; ?>

    <?php if ($errorMessage): ?>
        <div id="errorAlert" class="text-center"><?= $errorMessage ?></div>
    <?php endif; ?>

    <form method="POST" class="mx-auto shadow p-4 rounded bg-light" style="max-width: 600px;">
        <div class="mb-3">
            <label class="form-label">Användarnamn:</label>
            <input type="text" name="u_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Lösenord:</label>
            <input type="password" name="u_password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">E-post:</label>
            <input type="email" name="u_email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Roll-ID (u_role_fk):</label>
            <select name="u_role_fk" class="form-select" required>
                <option value="1">Admin</option>
                <option value="2">Användare</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Status (u_status):</label>
            <select name="u_status" class="form-select" required>
                <option value="1">Aktiv</option>
                <option value="0">Inaktiv</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary w-100 mb-3">Lägg till användare</button>
        <a href="user_list.php" class="btn btn-outline-secondary w-100">Tillbaka till användarlista</a>
    </form>
</main>

<?php include "includes/footer.php"; ?>
