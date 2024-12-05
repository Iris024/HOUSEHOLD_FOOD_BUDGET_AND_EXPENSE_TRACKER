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

$sql = "SELECT * FROM budgets";
$stmt = $conn->query($sql);

if ($stmt === false) {
    die("Error executing query: " . $conn->errorInfo()[2]);
}

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $totalPrice += $row['price']; 
}

$remainingBudget = $budget - $totalPrice;

if ($remainingBudget >= 0) {
    $budgetStatus = 'Budget Met';
    $statusClass = '';
} else {
    $budgetStatus = 'Budget Not Met';
    $statusClass = 'budget-not-met';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Report</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="stylee.css">
</head>
<body>
<header>
    <a href="#" class="logo"><i class="fas fa-coins"></i> BudgetPLates</a>
    <ul class="navbar">
        <li><a href="homepage.php" class="home-active">Home</a></li>
        <li><a href="manage_income.php">Income</a></li>
        <li><a href="budget.php">Budget</a></li>
        <li><a href="report.php">Report</a></li>
        <li><a href="about.php">About</a></li>
    </ul>
</header>

<div class="receipt-container">
    <h1>Budget Report</h1>
    <h2><span>Remaining Budget: </span>₱<?php echo number_format($remainingBudget, 2); ?></h2>
    <h2 class="<?php echo $statusClass; ?>">Status: <?php echo $budgetStatus; ?></h2>
</div>

<footer>
    <p>&copy; 2024 BudgetPlates. All rights reserved.</p>
</footer>
</body>
</html>
