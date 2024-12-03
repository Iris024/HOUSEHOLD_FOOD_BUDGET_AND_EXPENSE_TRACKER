<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined">
    <link rel="stylesheet" href="homepage.css?v=1">
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
            <li><a href="about.php">About</a></li>
            <li><a href="report.php">Report</a></li>
        </ul>
        <nav>
        <li>
                <img src="https://images.rawpixel.com/image_png_social_square/cHJpdmF0ZS9sci9pbWFnZXMvd2Vic2l0ZS8yMDIzLTAxL3JtNjA5LXNvbGlkaWNvbi13LTAwMi1wLnBuZw.png"  class="profile" alt=""></i>
            <ul>
                <i class="sub-item">
                    <span class="material-icons-outlined">account</span>
                    <p>Account</p>
                </i>
                <i class="sub-item">
                    <span class="material-icons-outlined">dashboard</span>
                    <p>Dashboard</p>
                </i>
                <i class="sub-item">
                    <span class="material-icons-outlined">emergency</span>
                    <p>Emergency Finances</p>
                </i>
                <i class="sub-item">
                    <span class="material-icons-outlined">notification</span>
                    <p>Notification</p>
                </i>
                <i class="sub-item">
                    <span class="material-icons-outlined">logout</span>
                    <p>Log-out</p>
                </i>
            </ul>
        </nav>
    </header>
    
    <div class="container" id="Homepage">
    <div class="homepage-content">
        <h1>Welcome to BudgetPlates!</h1>
        <p>Easily track your spending, plan meals, and stay on budget.</p>
        <p>Manage your finances and enjoy smart, stress-free budgeting with us!</p>
    </div>
</div>

</body>
</html>
