<?php

class Announcement {

    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllAnnouncements() {

        $query = "SELECT announcements.*,
                         users.FullName AS PostedBy
                  FROM announcements
                  INNER JOIN users
                  ON announcements.UserID = users.UserID
                  ORDER BY announcements.AnnouncementID DESC";

        $stmt = $this->conn->query($query);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLatestAnnouncements($limit = 5) {

        $limit = (int)$limit;

        $query = "SELECT announcements.*,
                         users.FullName AS PostedBy
                  FROM announcements
                  INNER JOIN users
                  ON announcements.UserID = users.UserID
                  ORDER BY announcements.AnnouncementID DESC
                  LIMIT $limit";

        $stmt = $this->conn->query($query);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addAnnouncement($user_id, $title, $content, $date_posted) {

        $query = "INSERT INTO announcements
                  (UserID, Title, Content, DatePosted)
                  VALUES (?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([
            $user_id,
            $title,
            $content,
            $date_posted
        ]);
    }

    public function deleteAnnouncement($announcement_id) {

        $query = "DELETE FROM announcements WHERE AnnouncementID = ?";

        $stmt = $this->conn->prepare($query);

        return $stmt->execute([$announcement_id]);
    }
}

?>
