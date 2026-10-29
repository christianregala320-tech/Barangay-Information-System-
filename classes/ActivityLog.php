<?php

class ActivityLog {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function addLog($user_id, $activity) {

        $query = "INSERT INTO activity_logs
                  (UserID, Activity, DateTime)
                  VALUES (?, ?, NOW())";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            $user_id,
            $activity
        ]);
    }

    public function getAllLogs() {

        $query = "SELECT activity_logs.*,
                         users.FullName
                  FROM activity_logs
                  INNER JOIN users
                  ON activity_logs.UserID = users.UserID
                  ORDER BY activity_logs.LogID DESC";

        $stmt = $this->conn->query($query);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
