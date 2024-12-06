<?php
session_start();
require_once 'connect.php';

$db = new Database();
$conn = $db->getConnect();

$budget = 0;
$remainingBudget = 0;
$totalPrice = 0;
$totalIncome = 0;

if (!isset($_SESSION['user_id'])) {
    header("Location: indexReg.php");
    exit;
}

$user_id = $_SESSION['user_id'];  


$sql_income = "SELECT amount, id FROM incomes WHERE user_id = :user_id ORDER BY date_received DESC LIMIT 1";
$stmt_income = $conn->prepare($sql_income);
$stmt_income->bindParam(':user_id', $user_id);
$stmt_income->execute();
$row_income = $stmt_income->fetch(PDO::FETCH_ASSOC);

if ($row_income) {
    $totalIncome = $row_income['amount'];  
    $income_id = $row_income['id'];        
} else {
    echo "<script>alert('No income found for this user.');</script>";
    exit;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['set_budget'])) {
        $budget = $_POST['budget'];

 
        if ($budget > $totalIncome) {
            echo "<script>alert('Your budget cannot exceed your total income of ₱" . number_format($totalIncome, 2) . "');</script>";
        } else {
            $_SESSION['budget'] = $budget;  
        }
    } elseif (isset($_POST['add_item'])) {
        $item = $_POST['item'];
        $price = $_POST['price'];
        $budget = $_SESSION['budget'];

        $sql = "INSERT INTO budgets (user_id, income_id, item, price, budget) 
                VALUES (:user_id, :income_id, :item, :price, :budget)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':income_id', $income_id);
        $stmt->bindParam(':item', $item);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':budget', $budget);
        $stmt->execute();
    } elseif (isset($_POST['update_price'])) {
       
        $item_id = $_POST['item_id'];
        $new_price = $_POST['new_price'];

        $sql_update = "UPDATE budgets SET price = :price WHERE id = :item_id AND user_id = :user_id";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bindParam(':price', $new_price);
        $stmt_update->bindParam(':item_id', $item_id);
        $stmt_update->bindParam(':user_id', $user_id);
        $stmt_update->execute();
    } elseif (isset($_POST['delete_item'])) {
       
        $item_id = $_POST['item_id'];

        $sql_delete = "DELETE FROM budgets WHERE id = :item_id AND user_id = :user_id";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bindParam(':item_id', $item_id);
        $stmt_delete->bindParam(':user_id', $user_id);
        $stmt_delete->execute();
    }
}


$sql = "SELECT * FROM budgets WHERE user_id = :user_id AND income_id = :income_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->bindParam(':income_id', $income_id);
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);


foreach ($items as $row) {
    $totalPrice += $row['price'];
}

if (isset($_SESSION['budget'])) {
    $remainingBudget = $_SESSION['budget'] - $totalPrice;
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css?v=1.0">
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
        <li><a href="report.php">Report</a></li>
        <li><a href="about.php">About</a></li>
    </ul>
</header>

<div class="main-content">
    <div class="receipt-container">
        <h2>Set Your Budget</h2>
        <form method="post" class="form-group">
            <label for="budget">Overall Budget:</label>
            <input type="text" name="budget" value="<?php echo isset($_SESSION['budget']) ? $_SESSION['budget'] : '0'; ?>" required>
            <button type="submit" name="set_budget">Set Budget</button>
        </form>

        <h3>Total Income: ₱<?php echo number_format($totalIncome, 2); ?></h3>

        <h3>Overall Budget: ₱<?php echo isset($_SESSION['budget']) ? number_format($_SESSION['budget'], 2) : '0.00'; ?></h3>
    </div>

    <div class="receipt-container">
        <h1>Item Details</h1>

        <h3>Add an Item</h3>
        <form method="post" class="form-group">
            <label for="item">Item Name:</label>
            <input type="text" name="item" required><br><br>
            <label for="price">Estimated Price:</label>
            <input type="text" name="price" required><br><br>
            <button type="submit" name="add_item">Add Item</button>
        </form>
    </div>
</div>

<div class="items-added-section">
    <table id="itemTable" class="display">
        <caption>Items Added</caption>
        <thead>
            <tr>
                <th>Item</th>
                <th>Price (₱)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($totalPrice > 0) {
                foreach ($items as $row) {
                    echo "<tr>
                            <td>{$row['item']}</td>
                            <td>" . number_format($row['price'], 2) . "</td>
                            <td>
                                <form method='post' style='display:inline;'>
                                    <input type='hidden' name='item_id' value='{$row['id']}'>
                                    <input type='number' name='new_price' value='{$row['price']}' required>
                                    <button type='submit' name='update_price'>Update Price</button>
                                </form>
                                <form method='post' style='display:inline;'>
                                    <input type='hidden' name='item_id' value='{$row['id']}'>
                                    <button type='submit' name='delete_item'>Delete</button>
                                </form>
                            </td>
                        </tr>";
                }
            }
            ?>
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td>Total Price:</td>
                <td id="totalPrice">₱<?php echo number_format($totalPrice, 2); ?></td>
            </tr>
            <tr class="remaining-budget">
                <td>Remaining Budget:</td>
                <td id="remainingBudget">₱<?php echo number_format($remainingBudget, 2); ?></td>
            </tr>
        </tfoot>
    </table>

    <form method="post" class="form-group clear-btn" action="">
        <button type="submit" name="clear_items">Clear All Items</button>
    </form>
</div>

</body>
</html>
