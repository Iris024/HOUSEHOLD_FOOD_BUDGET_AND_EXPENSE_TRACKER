<?php
session_start();  // Start the session at the very top
include("connect.php");

$db = new Database();
$conn = $db->getConnect(); // Initialize $conn with the database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage/header</title>
    <link rel="stylesheet" href="style.css">
    
</head>
<body>
    <nav>
        <div class="menu">
            <div class="logo">
                <a href="#">BudgetPlates</a>
            </div>
            <ul>
                <li><a href="#home">HOME</a></li>
                <li><a href="#income">INCOME</a></li>
                <li><a href="#budget">BUDGET</a></li>
                <li><a href="#emergency-finances">EMERGENCY FINANCES</a></li>
                <li><a href="#about-us">ABOUT US</a></li>
            </ul>
        </div>
    </nav>
    <div class="img"></div>
</body>
</html>
