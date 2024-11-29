<?php
session_start();
include("connect.php");

$db = new Database();
$conn = $db->getConnect();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <a href="#" class="logo">BudgetPLates</a>
        <ul class="navbar">
            <li><a href="home" class="home-active">Home</a></li>
            <li><a href="income.php">Income</a></li>
            <li><a href="budget.php">Budget</a></li>
            <li><a href="emergency-finances.php">Emergency Finances</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="log-out.php">Log-out</a></li>
            <li><a href="profile.php">Profile</a></li>
        </ul>
    </header>

    <div class="homepage-content">
        <!-- Your homepage content goes here -->
    </div>
</body>
</html>
