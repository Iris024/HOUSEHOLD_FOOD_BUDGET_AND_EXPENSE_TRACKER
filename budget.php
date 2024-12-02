<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['user_id'])) {
    echo "
    <script>
        alert('Please log in first.');
        window.location.href = 'indexReg.php';
    </script>";
    exit();
}

$pdo = new PDO('mysql:host=localhost;dbname=tracker_db;charset=utf8', 'root', '');
$tracker = new BudgetTracker($pdo);

$income_id = isset($_GET['income_id']) ? $_GET['income_id'] : null;
$user_id = $_SESSION['user_id']; // Assuming user_id is set after login

// Default values in case of an invalid or missing income_id
$expenses = [];
$totalExpenses = 0;
$remainingBudget = 0;
$budget = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_expenses'])) {
    $budget_amount = (float)$_POST['budget'];
    $items = [];

    foreach ($_POST['expense_items'] as $index => $itemName) {
        $items[] = [
            'name' => $itemName,
            'price' => (float)$_POST['expense_prices'][$index]
        ];
    }
    
    // Add expenses to the database
    $tracker->addExpenses($user_id, $income_id, $budget_amount, $items);
}

// Get data from database if income_id is available
if ($income_id) {
    $expenses = $tracker->getExpenses($income_id); // Pass the income_id as an argument
    $totalExpenses = $tracker->getTotalExpenses($income_id); // Pass the income_id as an argument
    $budget = $tracker->getBudgetAmount($income_id); // Pass the income_id as an argument
    $remainingBudget = $budget - $totalExpenses;
}

class BudgetTracker {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function addExpenses($user_id, $income_id, $budget_amount, $items) {
        $stmt = $this->pdo->prepare("INSERT INTO budgets (user_id, income_id, budget_amount, item, price) VALUES (?, ?, ?, ?, ?)");
        
        foreach ($items as $item) {
            if (!empty($item['name']) && $item['price'] > 0) {
                $stmt->execute([$user_id, $income_id, $budget_amount, $item['name'], $item['price']]);
            }
        }
    }

    public function getExpenses($income_id) {
        $stmt = $this->pdo->prepare("SELECT item, price FROM budgets WHERE income_id = ?");
        $stmt->execute([$income_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalExpenses($income_id) {
        $stmt = $this->pdo->prepare("SELECT SUM(price) as total FROM budgets WHERE income_id = ?");
        $stmt->execute([$income_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public function getBudgetAmount($income_id) {
        $stmt = $this->pdo->prepare("SELECT budget_amount FROM budgets WHERE income_id = ? ORDER BY id DESC LIMIT 1");
        $stmt->execute([$income_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['budget_amount'] ?? 0;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Tracker</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h1>Budget Tracker</h1>

    <form method="POST">
        <label for="budget">Enter Budget:</label>
        <input type="number" name="budget" id="budget" step="0.01" value="<?= htmlspecialchars($budget) ?>" required>

        <h3>Add Expected Expenses</h3>
        <div id="expense-container">
            <div>
                <input type="text" name="expense_items[]" placeholder="Expense Item" required>
                <input type="number" name="expense_prices[]" placeholder="Price" step="0.01" required>
            </div>
        </div>

        <button type="button" onclick="addExpenseRow()">Add Another Item</button>
        <br><br>
        <button type="submit" name="add_expenses">Add Expenses</button>
    </form>

    <h3>Expenses</h3>
    <?php if (!empty($expenses)): ?>
        <table>
            <thead>
                <tr>
                    <th>Expense Item</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($expenses as $expense): ?>
                    <tr>
                        <td><?= htmlspecialchars($expense['item']) ?></td>
                        <td><?= htmlspecialchars($expense['price']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No expenses added yet.</p>
    <?php endif; ?>

    <h3>Total Expenses: <?= htmlspecialchars($totalExpenses) ?></h3>
    <h3>Remaining Budget: <?= htmlspecialchars($remainingBudget) ?></h3>

    <script>
        function addExpenseRow() {
            const container = document.getElementById('expense-container');
            const newRow = document.createElement('div');
            newRow.innerHTML = '<input type="text" name="expense_items[]" placeholder="Expense Item" required>' +
                               '<input type="number" name="expense_prices[]" placeholder="Price" step="0.01" required>';
            container.appendChild(newRow);
        }
    </script>
</body>
</html>
