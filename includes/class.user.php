<?php

class user {
    public $errorMessage;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    private function cleanInput($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }


    public function checkUserRegisterInput() {
        $error = 0;
        $cleanEmail = $this->cleanInput($_POST['email']);
        
        if (isset($_POST['register'])) {
            
            $cleanName = $this->cleanInput($_POST['username']);
            $stmt_checkIfUserExist = $this->conn->prepare("SELECT * FROM table_users WHERE u_name = :uname OR u_email = :email");
            $stmt_checkIfUserExist->bindValue(":uname", $cleanName, PDO::PARAM_STR);
            $stmt_checkIfUserExist->bindValue(":email", $cleanEmail, PDO::PARAM_STR);
            $stmt_checkIfUserExist->execute();
        }

        $userNameMatch = $stmt_checkIfUserExist->fetch();

        if (!empty($userNameMatch)) {
            if ($userNameMatch['u_name'] == $cleanName) {
                $this->errorMessage .= " | Username is already taken";
                $error=1;
            }

            if (!empty($userNameMatch)) {
                if ($userNameMatch['u_email'] == $cleanEmail) {
                    $this->errorMessage .= " | Email is already in use";
                    $error=1;
                }
            }
        }
        
        if (isset($_POST['editaccount']) && $_POST['password'] == "") {

        }

        else {
            if ($_POST['password'] != $_POST['confpassword']) {
                $this->errorMessage .= " | PASSWORDS DO NOT MATCH";
                $error=1;
            }

            if (strlen($_POST['password']) < 8) {
                $this->errorMessage .= " | password doest not meet requirements";
                $error=1; 
            }
        }

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errorMessage .= " | Invalid email format";
            $error=1;
        }

        if ($error !=0) {
            echo $this->errorMessage;
            return $this->errorMessage;
        }

        else {
            return "Success!";
        }

    }

    public function register() {
        $cleanName = $this->cleanInput($_POST['username']);
        $cleanEmail = $this->cleanInput($_POST['email']);
        //Encrypt password with the password hash-function
        $encryptedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt_insertUser = $this->conn->prepare("INSERT INTO table_users (u_name, u_email, u_password, u_status)
		VALUES (:u_name, :user_email, :u_password, 1)");
		$stmt_insertUser->bindParam(':u_name', $cleanName, PDO::PARAM_STR);
		$stmt_insertUser->bindParam(':user_email', $cleanEmail, PDO::PARAM_STR);
		$stmt_insertUser->bindParam(':u_password', $encryptedPassword, PDO::PARAM_STR);
		$check = $stmt_insertUser->execute();

        if ($check) {
            return "User created successfully!";
        }

        else {
            return "Something went wrong.";
        }
    }

    public function logIn() {
        $cleanName = $this->cleanInput($_POST['username']);

        $stmt_checkIfUserExist = $this->conn->prepare("SELECT * FROM table_users WHERE u_name = :uname OR u_email = :email");
        $stmt_checkIfUserExist->bindValue(":uname", $cleanName, PDO::PARAM_STR);
        $stmt_checkIfUserExist->bindValue(":email", $cleanName, PDO::PARAM_STR);
        $stmt_checkIfUserExist->execute();
        $userNameMatch = $stmt_checkIfUserExist->fetch();

        if (!$userNameMatch) {
            $this->errorMessage = "No such user or email in database";
            return $this->errorMessage;
        }

        $checkPasswordMatch = password_verify($_POST["password"], $userNameMatch["u_password"]);

        if($checkPasswordMatch == true) {
            $_SESSION['u_name'] = $userNameMatch['u_name'];
            $_SESSION['u_role'] = $userNameMatch['u_role_fk'];
            $_SESSION['u_id'] = $userNameMatch['u_id'];
            return "Success!";
         }
         
         else {
            $this->errorMessage = "INVALID PASSWORD"; 
            return $this->errorMessage;  
         }
    }

    public function checkUserLogInStatus() {
        if (isset($_SESSION['u_id'])){
            return true;
        }

        else {
            return false;
        }
    }

    public function checkUserRole($req) {
        $stmt_checkRoleLevel = $this->conn->prepare("SELECT * FROM table_roles WHERE r_id = :user_role");
    $stmt_checkRoleLevel->bindValue(':user_role', $_SESSION['u_role'], PDO::PARAM_STR);
    $stmt_checkRoleLevel->execute();
    $currentUserRoleInfo = $stmt_checkRoleLevel->fetch();

    // Check if fetch() returned false
    if ($currentUserRoleInfo === false) {
        $this->errorMessage = "Role not found in the database.";
        return false;
    }

    // Fix the typo in the column name
    if ($currentUserRoleInfo["r_level"] >= $req) {
        return true;
    } else {
        return false;
    }
    }

    public function redirect($url) {
    header("Location: " .$url." ");
        exit();
    }


    public function logout() {
        session_unset();
        session_destroy();
        return true;
    }

    public function editUserInfo() {
        $error = 0;
        $cleanEmail = $this->cleanInput($_POST['updemail']);

		

        if (isset($_POST['password']) && $_POST['password'] != "") {
            $encryptedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $editUserInfo = $this->conn->prepare("UPDATE table_users SET  u_password = :u_password WHERE u_id = :u_id");
            $editUserInfo->bindParam(":u_password", $cleanPassword, PDO::PARAM_STR);
            $editUserInfo->bindParam(":u_id", $_SESSION['u_id'], PDO::PARAM_STR);
            $check = $editUserInfo->execute();
        }

        else {
            $editUserInfo = $this->conn->prepare("UPDATE table_users SET u_email = :u_email WHERE u_id = :u_id");
            $editUserInfo->bindParam(":u_email", $cleanEmail, PDO::PARAM_STR);
            $editUserInfo->bindParam(":u_id", $_SESSION['u_id'], PDO::PARAM_STR);
            $check = $editUserInfo->execute();
        }
        if ($check) {
            return true;
        }
    }

    public function getUserInfo($uid) {
        $stmt_userInfoQuery = $this->conn->prepare("SELECT * FROM table_users WHERE u_id = :u_id");
		$stmt_userInfoQuery->bindParam(':u_id', $uid, PDO::PARAM_STR);
		$stmt_userInfoQuery->execute();
        $userInfo = $stmt_userInfoQuery->fetch();
        return $userInfo;
    }

    public function searchUser() {
        $cleanParam = $this->cleanInput($_POST['searchinput']);
        $searchUserQuery = $this->conn->prepare("SELECT * FROM table_users WHERE u_name = :searchparam");
		$searchUserQuery->bindParam(':searchparam', $cleanParam, PDO::PARAM_STR);
        $searchUserQuery->execute();
        return $searchUserQuery;
    }
}

?>