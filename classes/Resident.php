<?php

class Resident {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllResidents() {

        $query = "SELECT residents.*,
                         households.HouseNumber,
                         households.Street,
                         households.Purok,
                         households.Barangay
                  FROM residents
                  LEFT JOIN households
                  ON residents.HouseholdID = households.HouseholdID
                  ORDER BY residents.ResidentID DESC";

        $stmt = $this->conn->query($query);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getResidentById($resident_id) {

        $query = "SELECT * FROM residents WHERE ResidentID = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$resident_id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addResident(
        $household_id,
        $first_name,
        $last_name,
        $middle_name,
        $birth_date,
        $gender,
        $civil_status,
        $occupation,
        $contact_number
    ) {

        $query = "INSERT INTO residents
                  (HouseholdID, FirstName, LastName, MiddleName, BirthDate,
                   Gender, CivilStatus, Occupation, ContactNumber)
                  VALUES
                  (:household_id, :first_name, :last_name, :middle_name, :birth_date,
                   :gender, :civil_status, :occupation, :contact_number)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            'household_id' => $household_id ?: null,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'middle_name' => $middle_name,
            'birth_date' => $birth_date,
            'gender' => $gender,
            'civil_status' => $civil_status,
            'occupation' => $occupation,
            'contact_number' => $contact_number
        ]);
    }

    public function updateResident(
        $resident_id,
        $household_id,
        $first_name,
        $last_name,
        $middle_name,
        $birth_date,
        $gender,
        $civil_status,
        $occupation,
        $contact_number
    ) {

        $query = "UPDATE residents
                  SET HouseholdID = ?,
                      FirstName = ?,
                      LastName = ?,
                      MiddleName = ?,
                      BirthDate = ?,
                      Gender = ?,
                      CivilStatus = ?,
                      Occupation = ?,
                      ContactNumber = ?
                  WHERE ResidentID = ?";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            $household_id ?: null,
            $first_name,
            $last_name,
            $middle_name,
            $birth_date,
            $gender,
            $civil_status,
            $occupation,
            $contact_number,
            $resident_id
        ]);
    }

    public function deleteResident($resident_id) {

        $query = "DELETE FROM residents WHERE ResidentID = ?";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([$resident_id]);
    }

    public function searchResidents($keyword) {

        $search = "%" . $keyword . "%";

        $query = "SELECT residents.*,
                         households.HouseNumber,
                         households.Street,
                         households.Purok,
                         households.Barangay
                  FROM residents
                  LEFT JOIN households
                  ON residents.HouseholdID = households.HouseholdID
                  WHERE residents.FirstName LIKE ?
                     OR residents.LastName LIKE ?
                     OR residents.MiddleName LIKE ?
                     OR residents.Gender LIKE ?
                     OR residents.CivilStatus LIKE ?
                     OR residents.Occupation LIKE ?
                     OR residents.ContactNumber LIKE ?
                     OR households.HouseNumber LIKE ?
                     OR households.Street LIKE ?
                     OR households.Purok LIKE ?
                     OR households.Barangay LIKE ?
                  ORDER BY residents.ResidentID DESC";

        $stmt = $this->conn->prepare($query);

        $stmt->execute([
            $search, $search, $search, $search, $search, $search,
            $search, $search, $search, $search, $search
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countResidents() {

        $query = "SELECT COUNT(*) FROM residents";

        return $this->conn->query($query)->fetchColumn();
    }

    public function countByGender($gender) {

        $query = "SELECT COUNT(*) FROM residents WHERE Gender = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->execute([$gender]);

        return $stmt->fetchColumn();
    }
}

?>
