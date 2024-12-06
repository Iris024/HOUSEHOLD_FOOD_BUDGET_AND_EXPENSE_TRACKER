<?php
// Include necessary files
require_once 'connect.php';
require_once 'income.php';
require_once 'notif.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to view your messages.');</script>";
    exit();
}

$database = new Database();
$db = $database->getConnect();

// Get the logged-in user's ID from the session
$user_id = $_SESSION['user_id'];

// Create an Income object
$income = new Income($db);

// Modify the query to get only records for the logged-in user
$query = "SELECT * FROM " . $income->tbl_name . " WHERE user_id = :user_id";
$stmt = $db->prepare($query);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

// Get the number of rows
$num = $stmt->rowCount();
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
        echo "<p>No messages found.</p>";
    }
    ?>
</div>

</body>
</html>
