<?php
class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $db_name = "tracker db";  // Make sure the database name is correct
    public $conn;

    public function getConnect() {
        $this->conn = null;
        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
            if ($this->conn->connect_error) {
                throw new Exception("Connection failed: " . $this->conn->connect_error);
            }
        } catch (Exception $e) {
            die("Database connection error: " . $e->getMessage());
        }
        return $this->conn;
    }
}
?>
