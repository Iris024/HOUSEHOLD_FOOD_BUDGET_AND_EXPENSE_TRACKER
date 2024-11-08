<?php

include 'connect.php';

session_start();

if(isset($_POST['register'])){
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $username = $_POST['username'];
    $password = md5($_POST['password']);  // Hashing the password

    // Check if the username already exists
    $checkuser_name = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $checkuser_name->bind_param("s", $username);
    $checkuser_name->execute();
    $result = $checkuser_name->get_result();

    if($result->num_rows > 0){
        echo "Username Already Exists!";
    } else {
        $insertQuery = $conn->prepare("INSERT INTO users (full_name, email, phone_number, username, password) VALUES (?, ?, ?, ?, ?)");
        $insertQuery->bind_param("sssss", $full_name, $email, $phone_number, $username, $password);
        if($insertQuery->execute()){
            $_SESSION['show_login'] = true;
            header("Location: register.php");
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

if(isset($_POST['Log-in'])){
    $username = $_POST['username'];
    $password = md5($_POST['password']);  // Hashing the password

    // Verify login credentials
    $sql = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $sql->bind_param("ss", $username, $password);
    $sql->execute();
    $result = $sql->get_result();
    if($result->num_rows > 0){
        session_start();
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $row['username'];
        header("Location: homepage.php");
        exit();
    } else {
        echo "Incorrect Username or Password";
    }
}

?>
