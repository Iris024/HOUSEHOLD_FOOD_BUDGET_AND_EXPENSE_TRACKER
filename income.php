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

$db = new Database();
$conn = $db->getConnect();

class Income {
    private $conn;
    protected $tbl_name = "incomes";

    private $id;
    private $source_name;
    private $amount;
    private $type;
    private $date_received;
    private $description;
    private $user_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getId() {
        return $this->id;
    }

    public function getSourceName() {
        return $this->source_name;
    }

    public function setSourceName($source_name) {
        $this->source_name = htmlspecialchars($source_name);
    }

    public function getAmount() {
        return $this->amount;
    }

    public function setAmount($amount) {
        if (is_numeric($amount) && $amount > 0) {
            $this->amount = htmlspecialchars($amount);
        } else {
            throw new Exception("Invalid amount");
        }
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = htmlspecialchars($type);
    }

    public function getDateReceived() {
        return $this->date_received;
    }

    public function setDateReceived($date_received) {
        $this->date_received = htmlspecialchars($date_received);
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = htmlspecialchars($description);
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function setUserId($user_id) {
        $this->user_id = htmlspecialchars($user_id);
    }

    public function getTableName() {
        return $this->tbl_name;
    }
    
    public function create() {
        $query = "INSERT INTO " . $this->getTableName() . " (source_name, amount, type, date_received, description, user_id) 
                  VALUES (:source_name, :amount, :type, :date_received, :description, :user_id)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':source_name', $this->source_name);
        $stmt->bindParam(':amount', $this->amount);
        $stmt->bindParam(':type', $this->type);
        $stmt->bindParam(':date_received', $this->date_received);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':user_id', $this->user_id);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
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
        $current_date = date('Y-m-d');
        if ($date_received > $current_date) {
            echo "<script>
             alert('The date cannot be in the future. Please select a valid date.');
             window.location.href = 'manage_income.php';
            </script>";
        } else {
        $income = new Income($conn);
        $income->setSourceName($source_name);
        $income->setAmount($amount);
        $income->setType($type);
        $income->setDateReceived($date_received);
        $income->setDescription($description);
        $income->setUserId($user_id);

            $income_id = $income->create();
            if ($income_id) {
                echo "<script>
                        alert('Income data successfully saved!');
                        window.location.href = 'manage_income.php'; // Redirect to homepage after success
                      </script>";
            } else {
                echo "<script>
                        alert('Failed to save income data. Please try again.');
                      </script>";
            }
        }
    }
}
?>
