<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['user_id'])) {
    echo "
    <script>
        alert('Please log in first.');
        window.location.href = 'index.php';
    </script>";
    exit();
}

$db = new Database();
$conn = $db->getConnect();

class Income {
    private $conn;
    private $tbl_name = "incomes";

    public $source_name;
    private $amount;
    private $type;
    public $date_received;
    public $description;
    public $user_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function setSourceName($source_name) {
        $this->source_name = htmlspecialchars($source_name);
    }

    public function setAmount($amount) {
        $this->amount = htmlspecialchars($amount);
    }

    public function setType($type) {
        $this->type = htmlspecialchars($type);
    }

    public function setDateReceived($date_received) {
        $this->date_received = htmlspecialchars($date_received);
    }

    public function setDescription($description) {
        $this->description = htmlspecialchars($description);
    }

    public function setUserId($user_id) {
        $this->user_id = htmlspecialchars($user_id);
    }

    public function create() {
        // Prepare SQL query to insert the income data into the 'incomes' table
        $query = "INSERT INTO " . $this->tbl_name . " (source_name, amount, type, date_received, description, user_id) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('ssssss', $this->source_name, $this->amount, $this->type, $this->date_received, $this->description, $this->user_id);
        return $stmt->execute();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $source_name = isset($_POST['source_name']) ? $_POST['source_name'] : null;
    $amount = isset($_POST['amount']) ? $_POST['amount'] : null;
    $type = isset($_POST['type']) ? $_POST['type'] : null;
    $date_received = isset($_POST['date_received']) ? $_POST['date_received'] : null;
    $description = isset($_POST['description']) ? $_POST['description'] : null;

    $user_id = $_SESSION['user_id'];

    if (empty($source_name) || empty($amount) || empty($type) || empty($date_received) || empty($description)) {
        echo "<script>alert('All fields are required.');</script>";
    } else {
        $income = new Income($conn);
        $income->setSourceName($source_name);
        $income->setAmount($amount);
        $income->setType($type);
        $income->setDateReceived($date_received);
        $income->setDescription($description);
        $income->setUserId($user_id);

        if ($income->create()) {
            echo "<script>
                    alert('Income data successfully saved!');
                    window.location.href = 'homepage.php'; // Redirect to homepage after success
                  </script>";
        } else {
            echo "<script>
                    alert('Failed to save income data. Please try again.');
                  </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Income Details</title>
</head>
<body>
    <h1>Submit Your Income Details</h1>
    <!-- Use POST method for form submission -->
    <form method="POST" action="">
        <label for="source_name">Source Name:</label>
        <input type="text" name="source_name" id="source_name" placeholder="Source Name" required>

        <label for="amount">Amount:</label>
        <input type="number" name="amount" id="amount" placeholder="Amount" required>

        <label for="type">Income Type:</label>
        <input type="text" name="type" id="type" placeholder="Type (e.g., Weekly)" required>

        <label for="date_received">Date Received:</label>
        <input type="date" name="date_received" id="date_received" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description" placeholder="Description" required></textarea>

        <button type="submit">Submit Income</button>
    </form>
</body>
</html>
