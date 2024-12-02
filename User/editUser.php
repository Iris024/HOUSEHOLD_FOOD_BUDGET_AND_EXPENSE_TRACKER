<?php
require_once 'connect.php';
require_once 'crudUser.php';

if (isset($_POST['id'])) {
    $id = htmlspecialchars($_POST['id']);
    $full_name = htmlspecialchars($_POST['full_name']);
    $email = htmlspecialchars($_POST['email']);
    $phone_number = htmlspecialchars($_POST['phone_number']);
    $username = htmlspecialchars($_POST['username']);
} else {
    echo "Invalid request.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
</head>
<body>
    <h2>Edit User</h2>
    <form method="POST" action="updateUser.php">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        Full Name: <input type="text" name="full_name" value="<?php echo $full_name; ?>" required>
        <br><br>
        Email: <input type="email" name="email" value="<?php echo $email; ?>" required>
        <br><br>
        Phone Number: <input type="phone_number" name="phone_number" value="<?php echo $phone_number; ?>" required>
        <br><br>
        Username: <input type="username" name="username" value="<?php echo $username; ?>" required>
        <br><br>
        <input type="submit" value="Update">
    </form>
</body>
</html>
