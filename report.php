<?php
session_start();
require_once 'connect.php';

$db = new Database();
$conn = $db->getConnect();

$budget = 0;
$remainingBudget = 0;
$totalPrice = 0;
$excessBudget = 0;

if (isset($_SESSION['budget'])) {
    $budget = $_SESSION['budget'];
}

if (isset($_SESSION['last_login_time'])) {
    $lastLoginTime = $_SESSION['last_login_time']; 
} else {
    $lastLoginTime = '1970-01-01 00:00:00';
}

$timeWindow = 30;

$timeLimit = date('Y-m-d H:i:s', strtotime("-$timeWindow minutes"));

$sql = "SELECT * FROM budgets WHERE added_at > :last_login_time AND added_at > :time_limit"; 
$stmt = $conn->prepare($sql);
$stmt->bindParam(':last_login_time', $lastLoginTime, PDO::PARAM_STR);
$stmt->bindParam(':time_limit', $timeLimit, PDO::PARAM_STR);
$stmt->execute();

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
    $excessBudget = $remainingBudget; 
} else {
    $budgetStatus = 'Budget Not Met';
    $statusClass = 'budget-not-met';
    $excessBudget = 0; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Report</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="stylessss.css">
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
    <h2><span>Initial Budget: </span>₱<?php echo number_format($budget, 2); ?></h2>
    <h2><span>Total Price of Items: </span>₱<?php echo number_format($totalPrice, 2); ?></h2>
    <h2><span>Remaining Budget: </span>₱<?php echo number_format($remainingBudget, 2); ?></h2>
    <h2 class="<?php echo $statusClass; ?>">Status: <?php echo $budgetStatus; ?></h2>
</div>

<footer>
    <p>&copy; 2024 BudgetPlates. All rights reserved.</p>
</footer>
</body>
</html>
