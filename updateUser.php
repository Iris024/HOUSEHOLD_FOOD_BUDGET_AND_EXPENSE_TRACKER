<?php
require_once 'connect.php';
require_once 'crudUser.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->getConnect();

    $user = new User($db);
    $user->id = htmlspecialchars(trim($_POST['id']));
    $user->full_name = htmlspecialchars(trim($_POST['full_name']));
    $user->email = htmlspecialchars(trim($_POST['email']));
    $user->phone_number = htmlspecialchars(trim($_POST['phone_number']));
    $user->username = htmlspecialchars(trim($_POST['username']));
    $user->password = $_POST['password']; 

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
            text: 'Data was Updated successfully!',
            icon: 'info'
        }).then((result) => {
            if(result.isConfirmed) {
                window.location.href = 'homepage.php';
            }
        });
        </script>
            
        </body>
        </html> ";
    } else {
        "
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
            text: 'Error when updating!',
            icon: 'info'
        }).then((result) => {
            if(result.isConfirmed) {
                window.location.href = 'homepage.php';
            }
        });
        </script>
            
        </body>
        </html> ";
    }
}
?>
