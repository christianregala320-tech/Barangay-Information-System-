<?php

class Household {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllHouseholds() {

        $query = "SELECT households.*,
                         COUNT(residents.ResidentID) AS TotalMembers
                  FROM households
                  LEFT JOIN residents
                  ON households.HouseholdID = residents.HouseholdID
                  GROUP BY households.HouseholdID
                  ORDER BY households.HouseholdID DESC";

        $stmt = $this->conn->query($query);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHouseholdById($household_id) {

        $query = "SELECT * FROM households WHERE HouseholdID = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$household_id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addHousehold(
        $house_number,
        $street,
        $purok,
        $barangay,
        $head_of_family
    ) {

        $query = "INSERT INTO households
                  (HouseNumber, Street, Purok, Barangay, HeadOfFamily)
                  VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            $house_number,
            $street,
            $purok,
            $barangay,
            $head_of_family
        ]);
    }

    public function updateHousehold(
        $household_id,
        $house_number,
        $street,
        $purok,
        $barangay,
        $head_of_family
    ) {

        $query = "UPDATE households
                  SET HouseNumber = ?,
                      Street = ?,
                      Purok = ?,
                      Barangay = ?,
                      HeadOfFamily = ?
                  WHERE HouseholdID = ?";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            $house_number,
            $street,
            $purok,
            $barangay,
            $head_of_family,
            $household_id
        ]);
    }

    public function deleteHousehold($household_id) {

        $query = "DELETE FROM households WHERE HouseholdID = ?";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([$household_id]);
    }

    public function countHouseholds() {

        return $this->conn->query("SELECT COUNT(*) FROM households")->fetchColumn();
    }
}

?>
