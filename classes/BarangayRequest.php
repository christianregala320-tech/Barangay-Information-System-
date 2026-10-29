<?php

class BarangayRequest {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllRequests() {

        $query = "SELECT requests.*,
                         CONCAT(residents.FirstName, ' ', residents.LastName) AS ResidentName
                  FROM requests
                  INNER JOIN residents
                  ON requests.ResidentID = residents.ResidentID
                  ORDER BY requests.RequestID DESC";

        $stmt = $this->conn->query($query);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addRequest($resident_id, $request_type, $request_date, $status) {

        $query = "INSERT INTO requests
                  (ResidentID, RequestType, RequestDate, Status)
                  VALUES (?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            $resident_id,
            $request_type,
            $request_date,
            $status
        ]);
    }

    public function updateStatus($request_id, $status) {

        $query = "UPDATE requests SET Status = ? WHERE RequestID = ?";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            $status,
            $request_id
        ]);
    }

    public function deleteRequest($request_id) {

        $query = "DELETE FROM requests WHERE RequestID = ?";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([$request_id]);
    }

    public function countPendingRequests() {

        $query = "SELECT COUNT(*) FROM requests WHERE Status = 'Pending'";

        return $this->conn->query($query)->fetchColumn();
    }
}

?>
