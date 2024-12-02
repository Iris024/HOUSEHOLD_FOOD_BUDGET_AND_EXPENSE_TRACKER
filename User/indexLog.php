<?php
session_start();
require_once 'connect.php';
require_once 'crudUser.php';

$database = new Database();
$db = $database->getConnect();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration & Log-In</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="user.css?v=1.0">
</head>
<body>
    <div class="container" id="Log-In">
        <h1 class="form-title">LOG-IN</h1>
        <form method="POST" action="loginUser.php"> <!-- Make sure loginUser.php is correct -->
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="username" id="username" placeholder="Username" required>
                <label for="username">Username</label>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Password" required>
                <label for="password">Password</label>
            </div>
            <p class="forget">
                <a href="#">Forget Password?</a>
            </p>
            <div class="input-group">
                <input type="submit" class="btn" value="Log-in" name="Log-in">
            </div>
        </form>
    </div>
    <script src="script.js"></script>
</body>
</html>
