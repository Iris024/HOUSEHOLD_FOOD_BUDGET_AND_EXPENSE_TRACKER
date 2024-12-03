<?php
session_start();
require_once 'connect.php';

$db = new Database();
$conn = $db->getConnect();

$budget = 0;
$remainingBudget = 0;
$totalPrice = 0;

if (isset($_SESSION['budget'])) {
    $budget = $_SESSION['budget'];
}

// Prepare and execute the query
$sql = "SELECT * FROM budgets";
$stmt = $conn->query($sql);

if ($stmt === false) {
    die("Error executing query: " . $conn->errorInfo()[2]);
}

// Fetch rows using PDO's fetch() method
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $totalPrice += $row['price'];
}

$remainingBudget = $budget - $totalPrice;

$budgetStatus = ($remainingBudget >= 0) ? 'Budget Met' : 'Budget Not Met';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Report</title>
</head>
<body>
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
    </header>
    <h1>Budget Report</h1>

    <h2>Initial Budget: ₱<?php echo number_format($budget, 2); ?></h2>
    <h2>Total Price of Items: ₱<?php echo number_format($totalPrice, 2); ?></h2>
    <h2>Remaining Budget: ₱<?php echo number_format($remainingBudget, 2); ?></h2>

    <h2>Status: <?php echo $budgetStatus; ?></h2>
</body>
</html>  
