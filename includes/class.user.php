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
            $stmt_checkIfUserExist = $this->conn->prepare("SELECT * FROM user_table WHERE user_name = :uname OR user_email = :email");
            $stmt_checkIfUserExist->bindValue(":uname", $cleanName, PDO::PARAM_STR);
            $stmt_checkIfUserExist->bindValue(":email", $cleanEmail, PDO::PARAM_STR);
            $stmt_checkIfUserExist->execute();
        }

        $userNameMatch = $stmt_checkIfUserExist->fetch();

        if (!empty($userNameMatch)) {
            if ($userNameMatch['user_name'] == $cleanName) {
                $this->errorMessage .= " | Username is already taken";
                $error=1;
            }

            if (!empty($userNameMatch)) {
                if ($userNameMatch['user_email'] == $cleanEmail) {
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
        $stmt_insertUser = $this->conn->prepare("INSERT INTO user_table (user_name, user_email, user_password, user_role_fk, user_status_fk) 
		VALUES (:user_name, :user_email, :user_password, 1, 1)");
		$stmt_insertUser->bindParam(':user_name', $cleanName, PDO::PARAM_STR);
		$stmt_insertUser->bindParam(':user_email', $cleanEmail, PDO::PARAM_STR);
		$stmt_insertUser->bindParam(':user_password', $encryptedPassword, PDO::PARAM_STR);
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

        $stmt_checkIfUserExist = $this->conn->prepare("SELECT * FROM user_table WHERE user_name = :uname OR user_email = :email");
        $stmt_checkIfUserExist->bindValue(":uname", $cleanName, PDO::PARAM_STR);
        $stmt_checkIfUserExist->bindValue(":email", $cleanName, PDO::PARAM_STR);
        $stmt_checkIfUserExist->execute();
        $userNameMatch = $stmt_checkIfUserExist->fetch();

        if (!$userNameMatch) {
            $this->errorMessage = "No such user or email in database";
            return $this->errorMessage;
        }

        $checkPasswordMatch = password_verify($_POST["password"], $userNameMatch["user_password"]);

        if($checkPasswordMatch == true) {
            $_SESSION['user_name'] = $userNameMatch['user_name'];
            $_SESSION['user_role'] = $userNameMatch['user_role_fk'];
            $_SESSION['user_id'] = $userNameMatch['user_id'];
            return "Success!";
         }
         
         else {
            $this->errorMessage = "INVALID PASSWORD"; 
            return $this->errorMessage;  
         }
    }

    public function checkUserLogInStatus() {
        if (isset($_SESSION['user_id'])){
            return true;
        }

        else {
            return false;
        }
    }

    public function checkUserRole($req) {
        $stmt_checkRoleLevel = $this->conn->prepare("SELECT * FROM role_table WHERE role_id = :user_role");
		$stmt_checkRoleLevel->bindValue(':user_role', $_SESSION['user_role'], PDO::PARAM_STR);
        $stmt_checkRoleLevel->execute();
        $currentUserRoleInfo = $stmt_checkRoleLevel->fetch();

        if ($currentUserRoleInfo["role_level"] >= $req) {
            return true;
        }

        else {
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
            $editUserInfo = $this->conn->prepare("UPDATE user_table SET user_email = :user_email, user_password = :user_password WHERE user_id = :user_id");
            $editUserInfo->bindParam(":user_email", $cleanEmail, PDO::PARAM_STR);
            $editUserInfo->bindParam(":user_password", $cleanPassword, PDO::PARAM_STR);
            $editUserInfo->bindParam(":user_id", $_SESSION['user_id'], PDO::PARAM_STR);
            $check = $editUserInfo->execute();
        }

        else {
            $editUserInfo = $this->conn->prepare("UPDATE user_table SET user_email = :user_email WHERE user_id = :user_id");
            $editUserInfo->bindParam(":user_email", $cleanEmail, PDO::PARAM_STR);
            $editUserInfo->bindParam(":user_id", $_SESSION['user_id'], PDO::PARAM_STR);
            $check = $editUserInfo->execute();
        }
        if ($check) {
            return true;
        }
    }

    public function getUserInfo($uid) {
        $stmt_userInfoQuery = $this->conn->prepare("SELECT * FROM user_table WHERE user_id = :user_id");
		$stmt_userInfoQuery->bindParam(':user_id', $uid, PDO::PARAM_STR);
		$stmt_userInfoQuery->execute();
        $userInfo = $stmt_userInfoQuery->fetch();
        return $userInfo;
    }

    public function searchUser() {
        $cleanParam = $this->cleanInput($_POST['searchinput']);
        $searchUserQuery = $this->conn->prepare("SELECT * FROM user_table WHERE user_name = :searchparam");
		$searchUserQuery->bindParam(':searchparam', $cleanParam, PDO::PARAM_STR);
        $searchUserQuery->execute();
        return $searchUserQuery;
    }
}

?>