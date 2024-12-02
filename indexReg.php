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
    <div class="container" id="Register">
        <h1 class="form-title">REGISTRATION</h1>
        <form method="POST" action="registerUser.php">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="full_name" id="full_name" placeholder="Full Name" required>
                <label for="full_name">Full Name</label>
            </div>
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="email" placeholder="Email" required>
                <label for="email">Email</label>
            </div>
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="phone_number" id="phone_number" placeholder="Phone Number" required>
                <label for="contact">Phone Number</label>
            </div>
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
            <div class="input-group">
            <input type="submit" class="btn" value="Register" name="register">
            </div>
        </form>
        <div class="links">
            <p>Already have an account? <button type="button" id="signInButton" onclick="window.location.href='indexLog.php';">Sign In</button></p>
    </div>
    <script src="script.js"></script>
</body>
</html>