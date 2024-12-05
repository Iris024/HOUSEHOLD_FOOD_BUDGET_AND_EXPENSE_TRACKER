<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined">
    <link rel="stylesheet" href="homepage.css?v=1">
</head>
<body>
    <header>
        <a href="#" class="logo"><i class="fas fa-coins"></i> BudgetPLates</a>
        <ul class="navbar">
            <li><a href="homepage.php">Home</a></li>
            <li><a href="manage_income.php">Income</a></li>
            <li><a href="budget.php">Budget</a></li>
            <li><a href="about.php">About</a></li>
            <li class="dropdown">
            <a href="#" class="dropdown-icon"><i class="fas fa-user-circle"></i></a>
            <ul class="dropdown-menu">
                <li><a href="#"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                <li><a href="emergency.php"><i class="fas fa-piggy-bank"></i> Emergency Finances</a></li>
                <li><a href="#"><i class="fas fa-bell"></i> Notification Settings</a></li>
                <li><a href="mood.php"><i class="fas fa-meh"></i> Mood</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Log-out</a></li>
            </ul>
            </li>
        </ul>
    </header>
    <div class="container" id="Homepage">
        <div class="homepage-content">
            <h1>Welcome to BudgetPlates!</h1>
            <p>Easily track your spending, plan meals, and stay on budget. Manage your finances and enjoy smart, stress-free budgeting with us!</p>
        </div>
</body>
</html>
