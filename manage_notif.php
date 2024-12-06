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

// Get the current time
$current_time = time();

// Fetching income data
$income = new Income($db);
$query = "SELECT * FROM " . $income->tbl_name . " WHERE user_id = :user_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

$num = $stmt->rowCount();

// Fetching budget data
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
    // Displaying income messages
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
    $valid_budgets = [];
    $unique_budget = null;
    $item_prices = []; 

    if ($num_budget > 0) {
        while ($row_budget = $stmt_budget->fetch(PDO::FETCH_ASSOC)) {
            $item = htmlspecialchars($row_budget['item']);
            $price = htmlspecialchars($row_budget['price']);
            $budget = htmlspecialchars($row_budget['budget']);
            $added_at = strtotime($row_budget['added_at']);

            if (($current_time - $added_at) <= 86400) {
                if ($unique_budget === null) {
                    $unique_budget = $budget;
                }

                $item_prices[] = "<strong>{$item}</strong> (₱" . number_format($price, 2) . ")";
            }
        }
        
        if (count($item_prices) > 0) {
            echo "<div class='budget-message'>";
            echo "<p>You have added items with a total budget of ₱" . number_format($unique_budget, 2) . " within the last 24 hours. These items include: " . implode(", ", $item_prices) . ".</p>";
            echo "</div>";
        } else {
            echo "<p>No budgets were added within the last 24 hours.</p>";
        }
    } else {
        echo "<p>No budget items found for your account.</p>";
    }
    ?>
</div>

</body>
</html>
