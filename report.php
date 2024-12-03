<?php
session_start();
include 'dbconnect.php';

$budget = 0;
$remainingBudget = 0;
$totalPrice = 0;

if (isset($_SESSION['budget'])) {
    $budget = $_SESSION['budget'];
}

$sql = "SELECT * FROM budgets";
$result = $conn->query($sql);

if ($result === false) {
    die("Error executing query: " . $conn->error);
}

while ($row = $result->fetch_assoc()) {
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
    <h1>Budget Report</h1>

    <h2>Initial Budget: ₱<?php echo number_format($budget, 2); ?></h2>
    <h2>Total Price of Items: ₱<?php echo number_format($totalPrice, 2); ?></h2>
    <h2>Remaining Budget: ₱<?php echo number_format($remainingBudget, 2); ?></h2>

    <h2>Status: <?php echo $budgetStatus; ?></h2>

    <br>

    <a href="homepage.php">back to homepage</a>
</body>
</html>
