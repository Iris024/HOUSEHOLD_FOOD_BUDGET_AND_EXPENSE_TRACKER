<?php
session_start(); // Start the session at the very top of the file

require_once 'connect.php';

$db = new Database();
$conn = $db->getConnect();

$budget = 0;
$remainingBudget = 0;
$totalPrice = 0;
$totalIncome = 0;

// Check if the user is logged in by verifying if `user_id` exists in the session
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];  // Retrieve the user_id from the session

// Get total income from the income table for the logged-in user
$sql_income = "SELECT SUM(amount) AS total_income FROM incomes WHERE user_id = :user_id";
$stmt_income = $conn->prepare($sql_income);
$stmt_income->bindParam(':user_id', $user_id);
$stmt_income->execute();
$row_income = $stmt_income->fetch(PDO::FETCH_ASSOC);
if ($row_income) {
    $totalIncome = $row_income['total_income'];
}

// Fetch the most recent income record for the logged-in user
$sql_income = "SELECT id FROM incomes WHERE user_id = :user_id ORDER BY date_received DESC LIMIT 1";
$stmt_income = $conn->prepare($sql_income);
$stmt_income->bindParam(':user_id', $user_id);
$stmt_income->execute();
$row_income = $stmt_income->fetch(PDO::FETCH_ASSOC);

if ($row_income) {
    $income_id = $row_income['id']; // Get the most recent income's ID
} else {
    // Handle the case where no income is found for the user
    echo "<script>alert('No income found for this user.');</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['set_budget'])) {
        $budget = $_POST['budget'];

        // Check if the budget exceeds the total income
        if ($budget > $totalIncome) {
            echo "<script>alert('Your budget cannot exceed your total income of ₱" . number_format($totalIncome, 2) . "');</script>";
        } else {
            $_SESSION['budget'] = $budget;  // Store budget in session
        }
    } elseif (isset($_POST['add_item'])) {
        $item = $_POST['item'];
        $price = $_POST['price'];
        $budget = $_SESSION['budget'];  // Retrieve the budget from session

        // Insert item into the budgets table with user_id, income_id, and overall budget
        $sql = "INSERT INTO budgets (user_id, income_id, item, price, budget) 
                VALUES (:user_id, :income_id, :item, :price, :budget)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':income_id', $income_id); // Add the income_id from the selected income record
        $stmt->bindParam(':item', $item);
        $stmt->bindParam(':price', $price);  // Bind the price to the query
        $stmt->bindParam(':budget', $budget);  // Bind the overall budget to the query
        $stmt->execute();
    }
}

// Fetch all items from the budget table for the current user
$sql = "SELECT * FROM budgets WHERE user_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate total price of all items added
foreach ($items as $row) {
    $totalPrice += $row['price'];
}

if (isset($_SESSION['budget'])) {
    // Calculate remaining budget: overall budget - total price of items
    $remainingBudget = $_SESSION['budget'] - $totalPrice;

    if (isset($_POST['clear_items'])) {
        // Clear all items from the budget table
        $sql_clear_items = "DELETE FROM budgets WHERE user_id = :user_id";
        $stmt_clear = $conn->prepare($sql_clear_items);
        $stmt_clear->bindParam(':user_id', $user_id);
        $stmt_clear->execute();
        
        // Clear session data related to budget
        unset($_SESSION['budget']);  // Remove budget session data if it's stored

        // Re-fetch items to reflect changes after deletion
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Re-fetch updated data
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Budget Tracker</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#itemTable').DataTable();
        });
    </script>
</head>
<body>
<header>
        <a href="#" class="logo"><i class="fas fa-coins"></i> BudgetPLates</a>
        <ul class="navbar">
            <li><a href="homepage.php" class="home-active">Home</a></li>
            <li><a href="manage_income.php">Income</a></li>
            <li><a href="budget.php">Budget</a></li>
            <li><a href="manage_expenses.php">Expense</a>
            <li><a href="about.php">About</a></li>
            <li><a href="profile.php"><i class="fas fa-user"></i></a></li>
        </ul>
    </header>

    <h1>Budget Tracker</h1>

    <form method="post">
        <label for="budget">Set Your Overall Budget: </label>
        <input type="number" name="budget" required value="<?php echo isset($_SESSION['budget']) ? $_SESSION['budget'] : '0'; ?>">
        <button type="submit" name="set_budget">Set Budget</button>
    </form>

    <!-- Display total income -->
    <h3>Total Income: ₱<?php echo number_format($totalIncome, 2); ?></h3>

    <h2>Overall Budget: ₱<?php echo isset($_SESSION['budget']) ? number_format($_SESSION['budget'], 2) : '0.00'; ?></h2>

    <h3>Add an Item (Estimated Budget)</h3>
    <form method="post">
        <label for="item">Item Name: </label>
        <input type="text" name="item" required><br><br>
        <label for="price">Estimated Price: </label>
        <input type="number" name="price" required><br><br>
        <button type="submit" name="add_item">Add Item</button>
    </form>

    <h3>Items Added:</h3>
    <table id="itemTable" class="display">
        <thead>
            <tr>
                <th>Item</th>
                <th>Price (₱)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Display items from the budgets table, including the overall budget
            foreach ($items as $row) {
                echo "<tr>
                        <td>{$row['item']}</td>
                        <td>" . number_format($row['price'], 2) . "</td>
                    </tr>";
            }
            ?>
        </tbody>
    </table>

    <h3>Total Price of Items: ₱<?php echo number_format($totalPrice, 2); ?></h3>

    <h3>Remaining Budget: ₱<?php echo number_format($remainingBudget, 2); ?></h3>

    <form method="post">
        <button type="submit" name="clear_items">Clear All Items</button>
    </form>

</body>
</html>
