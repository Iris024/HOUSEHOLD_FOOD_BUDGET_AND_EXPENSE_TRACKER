<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income Management</title>
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
     <link rel="stylesheet" href="homepage.css?v=1.0">
     
    <script>
        $(document).ready(function() {
            $('#incomeTable').DataTable();
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
            <li><a href="about.php">About</a></li>
            <li><a href="report.php">Report</a></li>
        </ul>
    </header>

    <div class="container" id="Income">;
    <h2>Income Details</h2>
    <form method="POST" action="income.php">
        Source Name: <input type="text" name="source_name" required>
        <br><br>
        Amount: <input type="text" name="amount" required>
        <br><br>
        Income Type: <select name="type" id="type" required>
            <option value="" disabled selected>Select Income Type</option>
            <option value="one-time">One-time</option>
            <option value="weekly">Weekly</option>
            <option value="monthly">Monthly</option>
            <option value="other">Other</option>
        </select>
        <br><br>
        Date Received: <input type="date" name="date_received" required>
        <br><br>
        Description: <input type="text" name="description" required>
        <br><br>
        <input type="submit" value="Create" required>
    </form>

    <h2>Incomes List</h2>
    <table id="incomeTable" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Source Name</th>
                <th>Amount</th>
                <th>Type</th>
                <th>Date Received</th>
                <th>Description</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require_once 'connect.php';
            require_once 'income.php';

            $database = new Database();
            $db = $database->getConnect();

            $income = new Income($db);
            $stmt = $income->read();
            $num = $stmt->rowCount();

            if($num > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                    echo "<tr>";
                    echo "<td>" .(isset($row['id']) ? htmlspecialchars($row['id']) : ''). "</td>";
                    echo "<td>" .(isset($row['source_name']) ? htmlspecialchars($row['source_name']) : ''). "</td>";
                    echo "<td>" .(isset($row['amount']) ? htmlspecialchars($row['amount']) : ''). "</td>";
                    echo "<td>" .(isset($row['type']) ? htmlspecialchars($row['type']) : ''). "</td>";
                    echo "<td>" .(isset($row['date_received']) ? htmlspecialchars($row['date_received']) : ''). "</td>";
                    echo "<td>" .(isset($row['description']) ? htmlspecialchars($row['description']) : ''). "</td>";
                    echo "</tr>";
                }
            }

            ?>
   
        </tbody>
    </table>

</body>
</html>