<?php
class USER {
    private $conn;
    public $errorMessage;

    public function __construct($conn) {
        $this->conn = $conn;
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function cleanInput($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    // ----------------------
    // Registration validation
    // ----------------------
    public function checkUserRegisterInput() {
        $error = 0;
        $cleanEmail = $this->cleanInput($_POST['email']);
        $cleanName  = $this->cleanInput($_POST['username']);

        if (isset($_POST['register'])) {
            $stmt_check = $this->conn->prepare("
                SELECT * FROM user_table WHERE user_name = :uname OR user_email = :email
            ");
            $stmt_check->bindValue(":uname", $cleanName, PDO::PARAM_STR);
            $stmt_check->bindValue(":email", $cleanEmail, PDO::PARAM_STR);
            $stmt_check->execute();
            $userMatch = $stmt_check->fetch();

            if ($userMatch) {
                if ($userMatch['user_name'] == $cleanName) {
                    $this->errorMessage .= " | Username is already taken";
                    $error = 1;
                }
                if ($userMatch['user_email'] == $cleanEmail) {
                    $this->errorMessage .= " | Email is already in use";
                    $error = 1;
                }
            }
        }

        if (!isset($_POST['editaccount']) || !empty($_POST['password'])) {
            if ($_POST['password'] != $_POST['confpassword']) {
                $this->errorMessage .= " | PASSWORDS DO NOT MATCH";
                $error = 1;
            }
            if (strlen($_POST['password']) < 8) {
                $this->errorMessage .= " | Password must be at least 8 characters";
                $error = 1;
            }
        }

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errorMessage .= " | Invalid email format";
            $error = 1;
        }

        return $error ? $this->errorMessage : "Success!";
    }

    // ----------------------
    // Register user
    // ----------------------
    public function register() {
        $cleanName  = $this->cleanInput($_POST['username']);
        $cleanEmail = $this->cleanInput($_POST['email']);
        $encryptedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $stmt_insert = $this->conn->prepare("
            INSERT INTO user_table (user_name, user_email, user_password, user_role_fk, user_status_fk)
            VALUES (:user_name, :user_email, :user_password, 1, 1)
        ");
        $stmt_insert->bindParam(':user_name', $cleanName, PDO::PARAM_STR);
        $stmt_insert->bindParam(':user_email', $cleanEmail, PDO::PARAM_STR);
        $stmt_insert->bindParam(':user_password', $encryptedPassword, PDO::PARAM_STR);

        return $stmt_insert->execute() ? "User created successfully!" : "Something went wrong.";
    }

    // ----------------------
    // Login
    // ----------------------
    public function logIn() {
        $cleanName = $this->cleanInput($_POST['username']);

        $stmt = $this->conn->prepare("
            SELECT * FROM user_table WHERE user_name = :uname OR user_email = :email
        ");
        $stmt->bindValue(":uname", $cleanName, PDO::PARAM_STR);
        $stmt->bindValue(":email", $cleanName, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch();

        if (!$user) {
            $this->errorMessage = "No such user or email in database";
            return $this->errorMessage;
        }

        if (password_verify($_POST["password"], $user["user_password"])) {
            $_SESSION['user_name'] = $user['user_name'];
            $_SESSION['user_role'] = $user['user_role_fk'];
            $_SESSION['user_id']   = $user['user_id'];
            return "Success!";
        } else {
            $this->errorMessage = "INVALID PASSWORD"; 
            return $this->errorMessage;
        }
    }

    // ----------------------
    // Check if user is logged in
    // ----------------------
    public function checkUserLogInStatus() {
        return isset($_SESSION['user_id']);
    }

    // ----------------------
    // Check user role against a required level
    // ----------------------
    public function checkUserRole($req) {
        if (!isset($_SESSION['user_role'])) return false;

        $stmt = $this->conn->prepare("SELECT * FROM table_roles WHERE r_id = :user_role");
        $stmt->bindValue(':user_role', $_SESSION['user_role'], PDO::PARAM_INT);
        $stmt->execute();
        $role = $stmt->fetch();

        return ($role && $role["r_level"] >= $req);
    }

    // ----------------------
    // Redirect helper
    // ----------------------
    public function redirect($url) {
        header("Location: $url");
        exit();
    }

    // ----------------------
    // Logout
    // ----------------------
    public function logout() {
        session_unset();
        session_destroy();
        return true;
    }

    // ----------------------
    // Edit user info
    // ----------------------
    public function editUserInfo() {
        $cleanEmail = $this->cleanInput($_POST['updemail']);

        if (!empty($_POST['password'])) {
            $encryptedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $this->conn->prepare("
                UPDATE user_table 
                SET user_email = :email, user_password = :password 
                WHERE user_id = :uid
            ");
            $stmt->bindParam(":email", $cleanEmail, PDO::PARAM_STR);
            $stmt->bindParam(":password", $encryptedPassword, PDO::PARAM_STR);
            $stmt->bindParam(":uid", $_SESSION['user_id'], PDO::PARAM_INT);
        } else {
            $stmt = $this->conn->prepare("
                UPDATE user_table 
                SET user_email = :email 
                WHERE user_id = :uid
            ");
            $stmt->bindParam(":email", $cleanEmail, PDO::PARAM_STR);
            $stmt->bindParam(":uid", $_SESSION['user_id'], PDO::PARAM_INT);
        }

        return $stmt->execute();
    }

    // ----------------------
    // Get user info
    // ----------------------
    public function getUserInfo($uid) {
        $stmt = $this->conn->prepare("SELECT * FROM user_table WHERE user_id = :uid");
        $stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // ----------------------
    // Search user by username
    // ----------------------
    public function searchUser() {
        $cleanParam = $this->cleanInput($_POST['searchinput']);
        $stmt = $this->conn->prepare("SELECT * FROM user_table WHERE user_name = :searchparam");
        $stmt->bindParam(':searchparam', $cleanParam, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>
