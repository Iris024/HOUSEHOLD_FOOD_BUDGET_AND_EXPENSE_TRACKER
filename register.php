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

    public function create() {
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
        $query = "UPDATE " . $this->tbl_name . " SET full_name = :full_name, email = :email, phone_number = :phone_number, password = :password WHERE username = :username";

        $stmt = $this->conn->prepare($query);

        $this->full_name = htmlspecialchars(strip_tags($this->full_name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->phone_number = htmlspecialchars(strip_tags($this->phone_number));
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->password = htmlspecialchars(strip_tags($this->password));

        $stmt->bindParam(":full_name", $this->full_name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":phone_number", $this->phone_number);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":username", $this->username);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->tbl_name . " WHERE username = :username";

        $stmt = $this->conn->prepare($query);

        $this->username = htmlspecialchars(strip_tags($this->username));

        $stmt->bindParam(":username", $this->username);

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
    $password = md5($_POST['password']);  

    $checkuser_name = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $checkuser_name->bind_param("s", $username);
    $checkuser_name->execute();
    $result = $checkuser_name->get_result();

    if ($result->num_rows > 0) {
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
                        window.location.href = 'register.php';
                    }
                });
            </script>
        </body>
        </html>";
    } else {
        $user = new User($conn);
        $user->full_name = $full_name;
        $user->email = $email;
        $user->phone_number = $phone_number;
        $user->username = $username;
        $user->password = $password;

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
                        text: 'Error: Something went wrong. User Registration failed!',
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
}

if (isset($_POST['Log-in'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = md5($_POST['password']); 

    $sql = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $sql->bind_param("ss", $username, $password);
    $sql->execute();
    $result = $sql->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $row['username'];
        header("Location: homepage.php");
        exit();
    } else {
        echo "Incorrect Username or Password";
    }
}

if (isset($_POST['update'])) {
    $full_name = htmlspecialchars($_POST['full_name']);
    $email = htmlspecialchars($_POST['email']);
    $phone_number = htmlspecialchars($_POST['phone_number']);
    $username = htmlspecialchars($_POST['username']);
    $password = md5($_POST['password']);

    $user = new User($conn);
    $user->full_name = $full_name;
    $user->email = $email;
    $user->phone_number = $phone_number;
    $user->username = $username;
    $user->password = $password;

    if ($user->update()) {
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
                    text: 'Your information has been successfully updated!',
                    icon: 'success'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'profile.php';
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
                    text: 'Error: Something went wrong. Update failed.',
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
