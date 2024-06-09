
<?php
class AdminModel {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getRoles() {
        $sql = "SELECT DISTINCT role FROM users";
        return $this->conn->query($sql);
    }

    public function getAdminName($user_id) {
        $sql = "SELECT name FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($name);
        $stmt->fetch();
        $stmt->close();
        return $name;
    }

    public function getUsersByRole($role = '') {
        if ($role) {
            $sql = "SELECT id, name, email, role FROM users WHERE role = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $role);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
        } else {
            $sql = "SELECT id, name, email, role FROM users";
            $result = $this->conn->query($sql);
        }
        return $result;
    }

    public function getAllNotes() {
        $sql = "SELECT id, title, content, user_id, created_at FROM notes";
        return $this->conn->query($sql);
    }
}
?>
