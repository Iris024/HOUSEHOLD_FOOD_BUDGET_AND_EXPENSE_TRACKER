<?php

require_once 'connect.php';
require_once 'crudUser.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = htmlspecialchars(trim($_POST['full_name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone_number = htmlspecialchars(trim($_POST['phone_number']));
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password']; 


    $database = new Database();
    $db = $database->getConnect();

    $user = new User($db);
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
                        window.location.href = 'indexLog.php';
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
                        window.location.href = 'indexReg.php';
                    }
                });
            </script>
        </body>
        </html>";
    }
}
?>