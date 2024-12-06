<?php
require_once 'connect.php';
require_once 'income.php';
require_once 'notif.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to view your messages.');</script>";
    exit();
}

$database = new Database();
$db = $database->getConnect();

$user_id = $_SESSION['user_id'];

$current_time = time();

$income = new Income($db);
$query = "SELECT * FROM " . $income->getTableName() . " WHERE user_id = :user_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

$num = $stmt->rowCount();

$query_budget = "SELECT * FROM budgets WHERE user_id = :user_id";
$stmt_budget = $db->prepare($query_budget);
$stmt_budget->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_budget->execute();
$num_budget = $stmt_budget->rowCount();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="notif.css?v=1.0">
</head>
<body>
<header>
    <a href="#" class="logo"><i class="fas fa-coins"></i> BudgetPLates</a>
    <ul class="navbar">
        <li><a href="homepage.php" class="home-active">Home</a></li>
        <li><a href="about.php">About</a></li>
    </ul>
</header>

<div class="container" id="Income">
    <h2>Messages</h2>
    <?php
    if ($num > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $source_name = htmlspecialchars($row['source_name']);
            $amount = htmlspecialchars($row['amount']);
            $type = htmlspecialchars($row['type']);
            $date_received = htmlspecialchars($row['date_received']);
            $description = htmlspecialchars($row['description']);

            echo "<div class='income-message'>";
            echo "<p>Congratulations! You have successfully added your <strong>{$type}</strong> income of <strong>{$amount}</strong> pesos from the source <strong>{$source_name}</strong> on <strong>{$date_received}</strong>. <strong>{$description}</strong></p>";
            echo "</div>";
        }
    } else {
        echo "<p>No messages found for your income.</p>";
    }
    ?>
    
    <?php
    if ($num_budget > 0) {
        $budget_message = "";
        $total_price = 0;
        $dates = [];

        while ($row_budget = $stmt_budget->fetch(PDO::FETCH_ASSOC)) {
            $item = htmlspecialchars($row_budget['item']);
            $price = htmlspecialchars($row_budget['price']);
            $budget = htmlspecialchars($row_budget['budget']);
            $added_at = strtotime($row_budget['added_at']);
            $formatted_date = date("F j, Y, g:i a", $added_at);
            
            $total_price += $price;
            $dates[] = $formatted_date;

            $budget_message .= "<strong>{$item}</strong> (₱" . number_format($price, 2) . "), ";
        }

        $remaining_budget = $budget - $total_price;
        $status = $remaining_budget >= 0 ? 'Budget Met' : 'Budget Not Met';
        $formatted_dates = implode(", ", array_unique($dates));

        echo "<div class='budget-message'>";
        echo "<p>You have added items with a total budget of ₱" .number_format($budget, 2) ." on <strong>" .$formatted_dates ."</strong>. These items include: " . rtrim($budget_message, ", ") .". <strong>Status: {$status}</strong>";
        echo "</div>";
    } else {
        echo "<p>No budget items found for your account.</p>";
    }
    ?>
</div>
</body>
</html>
