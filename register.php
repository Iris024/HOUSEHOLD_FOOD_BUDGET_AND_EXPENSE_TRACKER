<?php
session_start();
include 'connect.php';

$db = new Database();
$conn = $db->getConnect();

class User {
    private $conn;
    private $tbl_name = "users";

    public $id;
    public $full_name;
    public $email;
    public $phone_number;
    public $username;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function setFullName($full_name) {
        $this->full_name = htmlspecialchars($full_name);
    }

    public function setEmail($email) {
        $this->email = htmlspecialchars($email);
    }

    public function setPhoneNumber($phone_number) {
        $this->phone_number = htmlspecialchars($phone_number);
    }

    public function setUsername($username) {
        $this->username = htmlspecialchars($username);
    }

    public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function getFullName() {
        return $this->full_name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPhoneNumber() {
        return $this->phone_number;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function create() {
        $checkUserQuery = "SELECT * FROM " . $this->tbl_name . " WHERE username = ?";
        $stmt = $this->conn->prepare($checkUserQuery);
        $stmt->bind_param('s', $this->username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return false;
        }


        $query = "INSERT INTO " . $this->tbl_name . " (full_name, email, phone_number, username, password) 
                  VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('sssss', $this->full_name, $this->email, $this->phone_number, $this->username, $this->password);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->tbl_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function update() {
        $query = "UPDATE " . $this->tbl_name . " SET full_name = ?, email = ?, phone_number = ?, password = ? WHERE username = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param('sssss', $this->full_name, $this->email, $this->phone_number, $this->password, $this->username);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->tbl_name . " WHERE username = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bind_param('s', $this->username);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }
}

if (isset($_POST['register'])) {
    $full_name = htmlspecialchars($_POST['full_name']);
    $email = htmlspecialchars($_POST['email']); 
    $phone_number = htmlspecialchars($_POST['phone_number']); 
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    $user = new User($conn);
    $user->setFullName($full_name);
    $user->setEmail($email);
    $user->setPhoneNumber($phone_number);
    $user->setUsername($username);
    $user->setPassword($password); 

    if ($user->create()) {
        $_SESSION['show_login'] = true;
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>SweetAlert</title>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Congratulations, your account has been successfully created!',
                    icon: 'success'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'index.php';
                    }
                });
            </script>
        </body>
        </html>";
    } else {
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>SweetAlert</title>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Username Already Exists!',
                    icon: 'error'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'index.php';
                    }
                });
            </script>
        </body>
        </html>";
    }
}

if (isset($_POST['Log-in'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    $sql = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $sql->bind_param("s", $username);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_id'] = $row['id'];
            header("Location: homepage.php");
            exit();
        } else {
            echo "Incorrect Username or Password";
        }
    } else {
        echo "Incorrect Username or Password";
    }
}

if (isset($_POST['delete'])) {
    $username = htmlspecialchars($_POST['username']);

    $user = new User($conn);
    $user->username = $username;

    if ($user->delete()) {
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>SweetAlert</title>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    title: 'Success!',
                    text: 'Your account has been successfully deleted!',
                    icon: 'success'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'index.php';
                    }
                });
            </script>
        </body>
        </html>";
    } else {
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>SweetAlert</title>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    title: 'Error!',
                    text: 'Error: Something went wrong. Deletion failed.',
                    icon: 'error'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'profile.php';
                    }
                });
            </script>
        </body>
        </html>";
    }
}
?>