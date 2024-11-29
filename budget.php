<?php
// Database connection settings
$host = 'localhost'; // Server name
$dbname = 'tracker db'; // Database name
$username = 'root'; 
$password = ''; 
try {
    // Connect to MySQL using PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Linisin ang expenses tuwing nirere-load ang page
$pdo->exec("DELETE FROM budgets");

//dito yung paggawa o prng  paglikha ng klase na BudgetTracker
class BudgetTracker {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Save budget and expense to database
    public function addExpenses($userId, $budgetAmount, $items) {
        // Insert budget amount for the user
        $stmt = $this->pdo->prepare("INSERT INTO budgets (user_id, budget_amount) VALUES (?, ?)");
        $stmt->execute([$userId, $budgetAmount]);
        $budgetId = $this->pdo->lastInsertId(); // Get the inserted budget_id

        // Save each expense item with the associated budget_id
        $stmt = $this->pdo->prepare("INSERT INTO budgets (budget_id, user_id, item, price) VALUES (?, ?, ?, ?)");
        foreach ($items as $item) {
            if (!empty($item['name']) && $item['price'] > 0) {
                $stmt->execute([$budgetId, $userId, $item['name'], $item['price']]);
            }
        }
    }

    // Retrieve all expenses from database for a specific user
    public function getExpenses($userId) {
        $stmt = $this->pdo->prepare("SELECT item, price FROM budgets WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Calculate total expenses for a specific user
    public function getTotalExpenses($userId) {
        $stmt = $this->pdo->prepare("SELECT SUM(price) as total FROM budgets WHERE user_id = ?");
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    // Retrieve budget amount for a specific user
    public function getBudget($userId) {
        $stmt = $this->pdo->prepare("SELECT budget_amount FROM budgets WHERE user_id = ? ORDER BY budget_id DESC LIMIT 1");
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['budget_amount'] ?? 0;
    }
}

// Gumawa ng instance ng BudgetTracker na object
$tracker = new BudgetTracker($pdo);

// Example: Assuming you have a user_id (this could be obtained from a login system or session)
$userId = 1; // Replace with actual user ID

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_expenses'])) {
    $budgetAmount = (float)$_POST['budgets'];
    $items = [];
    foreach ($_POST['expense_items'] as $index => $itemName) {
        $items[] = [
            'name' => $itemName,
            'price' => (float)$_POST['expense_prices'][$index]
        ];
    }
    $tracker->addExpenses($userId, $budgetAmount, $items);
}

// Get data from database
$expenses = $tracker->getExpenses($userId);
$totalExpenses = $tracker->getTotalExpenses($userId);
$budget = $tracker->getBudget($userId);
$remainingBudget = $budget - $totalExpenses;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Budget Tracker</title>
</head>
<body>
    <h1>BUDGET TRACKER</h1>

    <!-- Form para itakda ang budget at magdagdag ng expenses -->
    <form method="POST">
        <h2>Set Your Budget and Add Expenses</h2>
        <!-- Budget input field -->
        <label for="budget">Enter Budget:</label>
        <input type="number" name="budget" id="budget" step="0.01" value="<?= htmlspecialchars($budget) ?>" required>

        <!-- Form para magdagdag ng expenses -->
        <h3>Add Expenses</h3>
        <div id="expense-container">
            <div>
                <input type="text" name="expense_items[]" placeholder="Expense Item" required>
                <input type="number" name="expense_prices[]" placeholder="Price" step="0.01" required>
            </div>
        </div>

        <button type="button" onclick="addExpenseRow()">Add Another Item</button>
        <button type="submit" name="add_expenses">Submit Expenses</button>
    </form>

    <!-- Display the list of expenses -->
    <h2>Expenses</h2>
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($expenses as $expense): ?>
            <tr>
                <td><?= htmlspecialchars($expense['item']) ?></td>
                <td><?= number_format($expense['price'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p>Total Estimated Price: <?= number_format($totalExpenses, 2) ?></p>

    <!-- Report Button -->
    <form action="report.php" method="GET">
        <button type="submit">View Report</button>
    </form>

    <script>
        function addExpenseRow() {
            const container = document.getElementById('expense-container');
            const newRow = document.createElement('div');
            newRow.innerHTML = `
                <input type="text" name="expense_items[]" placeholder="Expense Item" required>
                <input type="number" name="expense_prices[]" placeholder="Price" step="0.01" required>
            `;
            container.appendChild(newRow);
        }
    </script>

</body>
</html>
