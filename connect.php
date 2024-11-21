<?php
class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $db_name = "tracker db";
    public $conn;

    public function getConnect() {
        $this->conn = null;
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);

        if ($this->conn->connect_error) {
            echo "Failed to connect DB: " . $this->conn->connect_error;
        }

        return $this->conn;
    }
}
?>
