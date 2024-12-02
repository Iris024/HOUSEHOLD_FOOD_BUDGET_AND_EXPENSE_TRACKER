<?php

class User {
    private $conn;
    private $tbl_name = 'users';

    public $id;
    public $full_name;
    public $email;
    public $phone_number;
    public $username;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function setFullName($full_name) {
        $this->full_name = htmlspecialchars($full_name);
    }

    public function setEmail($email) {
        $this->email = htmlspecialchars($email);
    }

    public function setPhoneNumber($phone_number) {
        $this->phone_number = htmlspecialchars($phone_number);
    }

    public function setUsername($username) {
        $this->username = htmlspecialchars($username);
    }

    public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function getFullName() {
        return $this->full_name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPhoneNumber() {
        return $this->phone_number;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPassword() {
        return $this->password;
    }

    public function create() {
        $checkUserQuery = "SELECT * FROM " . $this->tbl_name . " WHERE username = :username";
        $stmt = $this->conn->prepare($checkUserQuery);
        $stmt->bindParam(':username', $this->username);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($result) {
            return false;
        }
    
        $query = "INSERT INTO " . $this->tbl_name . " (full_name, email, phone_number, username, password) 
                  VALUES (:full_name, :email, :phone_number, :username, :password)";
        
        $stmt = $this->conn->prepare($query);
    
        $stmt->bindParam(':full_name', $this->full_name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':phone_number', $this->phone_number);
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $this->password);
    
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

    public function update() {
        if (!empty($this->id)) {
            $query = "UPDATE " . $this->tbl_name . " 
                      SET full_name = :full_name, email = :email, phone_number = :phone_number, password = :password 
                      WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
    
            $stmt->bindParam(":full_name", $this->full_name);
            $stmt->bindParam(":email", $this->email);
            $stmt->bindParam(":phone_number", $this->phone_number);
            $stmt->bindParam(":password", $this->password);
            $stmt->bindParam(":id", $this->id);
        } else {
            return false;
        }
    
        if ($stmt->execute()) {
            return true;
        }
    
        return false;
    }
    

    public function delete() {
        $query = "DELETE FROM " . $this->tbl_name . " WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":id", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }


}
?>