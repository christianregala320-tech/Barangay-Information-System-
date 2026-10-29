<?php

class Certificate {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllCertificates() {

        $query = "SELECT certificates.*,
                         CONCAT(residents.FirstName, ' ', residents.LastName) AS ResidentName,
                         users.FullName AS IssuedBy
                  FROM certificates
                  INNER JOIN residents
                  ON certificates.ResidentID = residents.ResidentID
                  INNER JOIN users
                  ON certificates.UserID = users.UserID
                  ORDER BY certificates.CertificateID DESC";

        $stmt = $this->conn->query($query);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addCertificate(
        $resident_id,
        $user_id,
        $certificate_type,
        $date_issued,
        $purpose
    ) {

        $query = "INSERT INTO certificates
                  (ResidentID, UserID, CertificateType, DateIssued, Purpose)
                  VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            $resident_id,
            $user_id,
            $certificate_type,
            $date_issued,
            $purpose
        ]);
    }

    public function deleteCertificate($certificate_id) {

        $query = "DELETE FROM certificates WHERE CertificateID = ?";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([$certificate_id]);
    }

    public function countCertificates() {

        return $this->conn->query("SELECT COUNT(*) FROM certificates")->fetchColumn();
    }
}

?>
