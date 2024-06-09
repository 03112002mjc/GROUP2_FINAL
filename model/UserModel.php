<?php
include __DIR__ . "/../config/config.php";

    class UserModel {
        private $conn;

        public function __construct() {
            global $conn;
            $this->conn = $conn;
        }

        public function getAllUsers() {
            $sql = "SELECT * FROM users";
            $result = $this->conn->query($sql);
    
            if ($result->num_rows > 0) {
                $users = [];
                while ($row = $result->fetch_assoc()) {
                    $users[] = $row;
                }
                return $users;
            } else {
                return [];
            }
        }

        public function login($email, $password) {
            $email = $this->validate($email);
            $password = md5($this->validate($password)); // Use md5 for password validation

            $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ss', $email, $password);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user) {
                // Start session and set session variables
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];

                // Redirect based on user role
                if ($user['role'] === 'admin') {
                    header("Location: view/admin_dashboard.php");
                    exit();
                } else {
                    header("Location: view/student_dashboard.php");
                    exit();
                }
            } else {
                header("Location: index.php?error=Incorrect username or password.");
                exit();
            }
        }

        public function getUserByEmailAndPassword($email, $password) {
            $email = $this->validate($email);
            $password = md5($this->validate($password));
            
            $sql = "SELECT * FROM users WHERE email = ? AND password = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('ss', $email, $password);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        }

        public function checkAdminExists() {
            $sql = "SELECT COUNT(*) as count FROM users WHERE role = 'admin'";
            $result = $this->conn->query($sql);
            $row = $result->fetch_assoc();
            return $row['count'] > 0;
        }

        public function register($name, $email, $password) {
            $name = $this->validate($name);
            $eml = $this->validate($email);
            $pass = $this->validate($password);
        
            $user_data = 'name=' . $name . '&email=' . $eml;
        
            if (empty($name) || empty($eml) || empty($pass)) {
                header("Location: ../view/register.php?error=All fields are required&$user_data");
                exit();
            } else {
                $pass = md5($pass);
        
                $sql = "SELECT * FROM users WHERE email='$eml'";
                $result = mysqli_query($this->conn, $sql);
        
                if (mysqli_num_rows($result) > 0) {
                    header("Location: ../view/register.php?error=The email is taken try another&$user_data");
                    exit();
                } else {
                    $sql2 = "INSERT INTO users(name, email, password) VALUES('$name', '$eml', '$pass')";
                    $result2 = mysqli_query($this->conn, $sql2);
                    if ($result2) {
                        header("Location: ../view/register.php?success=Your account has been created successfully");
                        exit();
                    } else {
                        header("Location: ../view/register.php?error=Unknown error occurred&$user_data");
                        exit();
                    }
                }
            }
        }

        public function setupAdmin($name, $email, $password) {
            $name = $this->validate($name);
            $email = $this->validate($email);
            $password = md5($this->validate($password)); // Use md5 for password hashing
            $role = 'admin';

            // Check if an admin already exists
            $check_admin_sql = "SELECT id FROM users WHERE role = 'admin' LIMIT 1";
            $check_admin_result = $this->conn->query($check_admin_sql);

            if (!$check_admin_result) {
                return "Database error: " . $this->conn->error;
            }

            if ($check_admin_result->num_rows > 0) {
                return "Admin account already exists. This setup page is disabled.";
            }
            
            // Check if the email already exists
            $check_sql = "SELECT id FROM users WHERE email = ?";
            $check_stmt = $this->conn->prepare($check_sql);
            $check_stmt->bind_param("s", $email);
            $check_stmt->execute();
            $check_stmt->store_result();

            if ($check_stmt->num_rows > 0) {
                return "This account already exists. Please create a new one!";
            } else {
                // Insert new admin
                $insert_sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
                $stmt = $this->conn->prepare($insert_sql);
                $stmt->bind_param("ssss", $name, $email, $password, $role);

                if ($stmt->execute()) {
                    return "Admin account created successfully!";
                } else {
                    return "Error: " . $stmt->error;
                }
            }
        }

        public function getUserById($user_id) {
            $sql = "SELECT * FROM users WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();
            return $user;
        }
        public function updateUser($user_id, $name, $email, $role) {
            $sql = "UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sssi", $name, $email, $role, $user_id);
            $result = $stmt->execute();
            $stmt->close();
            return $result;
        }
    
        public function getRoles() {
            $sql_roles = "SELECT DISTINCT role FROM users";
            $result_roles = $this->conn->query($sql_roles);
            $roles = [];
            while ($row_role = $result_roles->fetch_assoc()) {
                $roles[] = $row_role['role'];
            }
            return $roles;
        }
    
        public function getUserNameById($user_id) {
            $sql = "SELECT name FROM users WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->bind_result($name);
            $stmt->fetch();
            $stmt->close();
            return $name;
        }

        public function getDistinctRoles() {
            $sql = "SELECT DISTINCT role FROM users";
            $result = $this->conn->query($sql);
            return $result;
        }
    
        public function getUsersByRole($role) {
            if ($role) {
                $sql = "SELECT id, name, email, role FROM users WHERE role = ?";
                $stmt = $this->conn->prepare($sql);
                $stmt->bind_param("s", $role);
                $stmt->execute();
                $result = $stmt->get_result();
            } else {
                $sql = "SELECT id, name, email, role FROM users";
                $result = $this->conn->query($sql);
            }
            return $result;
        }

        
    
        public function checkEmailExists($email) {
            $sql = "SELECT * FROM users WHERE email = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->num_rows > 0;
        }
    
        public function createUser($name, $email, $role, $password) {
            $sql = "INSERT INTO users (name, email, role, password) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssss", $name, $email, $role, $password);
            return $stmt->execute();
        }

        public function deleteUserAndNotes($user_id) {
            // Delete notes associated with the user
            $delete_notes_sql = "DELETE FROM notes WHERE user_id = ?";
            $stmt_notes = $this->conn->prepare($delete_notes_sql);
            $stmt_notes->bind_param("i", $user_id);
            $stmt_notes->execute();
            $stmt_notes->close();
    
            // Delete the user
            $delete_user_sql = "DELETE FROM users WHERE id = ?";
            $stmt_user = $this->conn->prepare($delete_user_sql);
            $stmt_user->bind_param("i", $user_id);
            $result = $stmt_user->execute();
            $stmt_user->close();
    
            return $result;
        }
        
        
        
        private function validate($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }



    }
?>
    
