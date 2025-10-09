<?php
session_start(); // Start session at the top
require_once 'includes/config.php'; // Config + DB + USER class

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Vänligen fyll i både användarnamn och lösenord.";
    } else {
        try {
            $stmt = $conn->prepare("SELECT * FROM table_users WHERE u_name = ?");
            $stmt->execute([$username]);
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($userRow) {
                // Verify password using bcrypt
                if (password_verify($password, $userRow['u_password'])) {
                    // Check if user is active
                    if ($userRow['u_status'] != 1) {
                        $error = "Ditt konto är inaktivt eller otillåtet.";
                    } else {
                        // Set session variables
                        $_SESSION['user_id'] = $userRow['u_id'];
                        $_SESSION['username'] = $userRow['u_name'];
                        $_SESSION['u_role_fk'] = $userRow['u_role_fk'];
                        $_SESSION['u_status'] = $userRow['u_status'];

                        // Redirect based on role
                        switch ($_SESSION['u_role_fk']) {
                            case 1: // Administrator
                                header("Location: book_panel.php");
                                exit();
                            case 2: // Moderator / Regular user
                                header("Location: user_panel.php");
                                exit();
                            default:
                                $error = "Otillåten inloggning.";
                        }
                    }
                } else {
                    $error = "Felaktigt användarnamn eller lösenord.";
                }
            } else {
                $error = "Felaktigt användarnamn eller lösenord.";
            }
        } catch (PDOException $e) {
            $error = "Databasfel: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="text-center mb-4">Logga in</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" class="mx-auto shadow p-4 rounded bg-white" style="max-width: 400px;">
        <div class="mb-3">
            <label class="form-label">Användarnamn:</label>
            <input type="text" name="username" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Lösenord:</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Logga in</button>
    </form>
</div>
</body>
</html>
