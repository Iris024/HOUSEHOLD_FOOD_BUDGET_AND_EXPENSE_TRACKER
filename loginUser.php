<?php
session_start();
require_once 'connect.php';
require_once 'crudUser.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];

    $database = new Database();
    $db = $database->getConnect();

    $sql = $db->prepare("SELECT * FROM users WHERE username = :username");
    $sql->bindParam(':username', $username);
    $sql->execute();

    $row = $sql->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_id'] = $row['id'];

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
                        text: 'Login successful!',
                        icon: 'success'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'homepage.php';
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
                        text: 'Incorrect username or password!',
                        icon: 'error'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'indexLog.php';
                        }
                    });
                </script>
            </body>
            </html>";
        }
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
                    text: 'Username does not exist!',
                    icon: 'error'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'indexReg.php';
                    }
                });
            </script>
        </body>
        </html>";
    }
}
?>