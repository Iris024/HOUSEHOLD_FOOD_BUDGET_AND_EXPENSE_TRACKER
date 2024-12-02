<?php
session_start();
require_once 'connect.php';

// Check if the user is logged in by verifying the session variable
if (!isset($_SESSION['user_id'])) {
    echo "
    <script>
        alert('Please log in first.');
        window.location.href = 'indexReg.php'; // Redirect to login page if not logged in
    </script>";
    exit();
}

$db = new Database();
$conn = $db->getConnect();

class Expenses {
    private $conn;
    private $tbl_name = "expenses";

    public $budget_id;
    private $amount;
    private $date_spent;
    public $description;
    public $user_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function setBudgetId($budget_id) {
        $this->budget_id = htmlspecialchars($budget_id);
    }

    public function setAmount($amount) {
        $this->amount = htmlspecialchars($amount);
    }

    public function setDateSpent($date_spent) {
        $this->date_spent = htmlspecialchars($date_spent);
    }

    public function setDescription($description) {
        $this->description = htmlspecialchars($description);
    }

    public function setUserId($user_id) {
        $this->user_id = htmlspecialchars($user_id);
    }

    public function create() {
        // Prepare SQL query to insert the income data into the 'incomes' table
        $query = "INSERT INTO " . $this->tbl_name . " (budget_id, amount,date_spent, description, user_id) 
                  VALUES (:budget_id, :amount, :date_spent, :description, :user_id)";

        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':budget_id', $this->budget_id);
        $stmt->bindParam(':amount', $this->amount);
        $stmt->bindParam(':date_spent', $this->date_spent);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':user_id', $this->user_id);
    
        if ($stmt->execute()) {
            return true;
        }
    
        return false;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->tbl_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get user input from the form
    $budget_id = isset($_POST['budget_id']) ? $_POST['budget_id'] : null;
    $amount = isset($_POST['amount']) ? $_POST['amount'] : null;
    $date_spent = isset($_POST['date_spent']) ? $_POST['date_spent'] : null;
    $description = isset($_POST['description']) ? $_POST['description'] : null;

    // Get user_id from session
    $user_id = $_SESSION['user_id']; // Assumed that user_id is set after login

    // Check if all fields are filled
    if (empty($budget_id) || empty($amount) || empty($date_spent) || empty($description)) {
        echo "<script>alert('All fields are required.');</script>";
    } else {
        // Instantiate the Income class and set the values
        $expenses = new Expenses($conn);
        $expenses->setBudgetId($budget_id);
        $expenses->setAmount($amount);
        $expenses->setDateSpent($date_spent);
        $expenses->setDescription($description);
        $expenses->setUserId($user_id);

        // Insert the income data into the database
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