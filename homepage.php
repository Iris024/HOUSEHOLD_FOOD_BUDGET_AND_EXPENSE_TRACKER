<?php
session_start();
require_once 'connect.php';
require_once 'crudUser.php';

$db = new Database();
$conn = $db->getConnect();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="homepage.css">
</head>
<body>
    <!-- Header Section -->
    <header>
        <a href="#" class="logo"><i class="fas fa-coins"></i> BudgetPLates</a>
        <ul class="navbar">
            <li><a href="homepage.php" class="home-active">Home</a></li>
            <li><a href="manage_income.php">Income</a></li>
            <li><a href="budget.php">Budget</a></li>
            <li><a href="manage_expenses.php">Expense</a></li>
            <li><a href="emergency_finances.php">Emergency Finances</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="log-out.php">Log-out</a></li>
            <li><a href="profile.php"><i class="fas fa-user"></i></a></li>
        </ul>
    </header>

    <!-- Main Homepage Content Section -->
    <div class="container" id="Homepage">
        <div class="homepage-content">
            <h1>Welcome to BudgetPlates!</h1>
            <p>Easily track your spending, plan meals, and stay on budget.</p>
            <p>Manage your finances and enjoy smart, stress-free budgeting with us!</p>
        </div>
    </div>

    <!-- End of Body Content -->
</body>
</html>
