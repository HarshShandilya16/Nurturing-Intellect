<?php
$servername = "localhost"; 
$username = "root"; 
$password = NULL; 
$dbname = "user"; 
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$name = $_POST['name'];
$email = $_POST['email'];
$pass = $_POST['password']; 
$phone = $_POST['phone'];
$address = $_POST['address'];
$stmt = $conn->prepare("INSERT INTO volunteer(Name, Email, Password, Contact, Address) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $name, $email, $pass, $phone, $address);

if ($stmt->execute()) {
    echo "New record created successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
