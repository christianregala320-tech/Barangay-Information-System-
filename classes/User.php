<?php

class User {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function login($username, $password) {

        $query = "SELECT * FROM users
                  WHERE Username = ? AND Status = 'Active'
                  LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$username]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['Password'])) {
            return $user;
        }

        return false;
    }

    public function getUserById($user_id) {

        $query = "SELECT UserID, FullName, Username, Role, ContactNumber, Status
                  FROM users
                  WHERE UserID = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$user_id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProfile($user_id, $full_name, $contact_number) {

        $query = "UPDATE users
                  SET FullName = ?, ContactNumber = ?
                  WHERE UserID = ?";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            $full_name,
            $contact_number,
            $user_id
        ]);
    }

    public function changePassword($user_id, $new_password) {

        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $query = "UPDATE users SET Password = ? WHERE UserID = ?";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            $hashed_password,
            $user_id
        ]);
    }
}

?>
