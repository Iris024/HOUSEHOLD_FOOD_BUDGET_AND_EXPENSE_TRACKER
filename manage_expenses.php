<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expenses Management</title>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- SweetAlert2 JS --> 
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
     <link rel="stylesheet" href=".css?v=1.0">
     
    <script>
        $(document).ready(function() {
            $('#expensesTable').DataTable();
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
            <li><a href="manage_expenses.php">Expense</a></li>
            <li><a href="emergency-finances.php">Emergency Finances</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="log-out.php">Log-out</a></li>
            <li><a href="profile.php"><i class="fas fa-user"></i></a></li>
        </ul>
    </header>

    <div class="container" id="Expenses">;
    <h2>Expenses Details</h2>
    <form method="POST" action="expenses.php">
        Amount: <input type="text" name="amount" required>
        <br><br>
        Date Spent: <input type="date" name="date_spent" required>
        <br><br>
        Description: <input type="text" name="description" required>
        <br><br>
        <input type="submit" value="Create" required>
    </form>

    <h2>Expenses List</h2>
    <table id="expensesTable" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Budget_id</th>
                <th>Amount</th>
                <th>Date Spent</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require_once 'connect.php';
            require_once 'expenses.php';

            $database = new Database();
            $db = $database->getConnect();

            $expenses = new Expenses($db);
            $stmt = $expenses->read();
            $num = $stmt->rowCount();

            if($num > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    echo "<tr>";
                    echo "<td>" .(isset($row['id']) ? htmlspecialchars($row['id']) : ''). "</td>";
                    echo "<td>" .(isset($row['budget_id']) ? htmlspecialchars($row['budget_id']) : ''). "</td>";
                    echo "<td>" .(isset($row['amount']) ? htmlspecialchars($row['amount']) : ''). "</td>";
                    echo "<td>" .(isset($row['date_spent']) ? htmlspecialchars($row['date_spent']) : ''). "</td>";
                    echo "<td>" .(isset($row['description']) ? htmlspecialchars($row['description']) : ''). "</td>";
                    echo "<td>
                    <form method='POST' action='editIncome.php' style='display:inline;'>
                      <input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "'>
                      <input type='hidden' name='budget_id' value='" . htmlspecialchars($row['budget_id']) . "'>
                      <input type='hidden' name='amount' value='" . htmlspecialchars($row['amount']) . "'>
                      <input type='hidden' name='date_spent' value='" . htmlspecialchars($row['date_spent']) . "'>
                      <input type='hidden' name='description' value='" . htmlspecialchars($row['description']) . "'>
                      <input type='submit' value='Edit'>
                    </form>
                    <form method='POST' action='deleteIncome.php' style='display:inline;'>
                      <input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "'>
                      <input type='submit' value='Delete'>
                    </form>
                    </td>";
                    echo "</tr>";
                }
            }

            ?>
   
        </tbody>
    </table>

</body>
</html>