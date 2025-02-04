<?php
session_start();

if (!isset($_SESSION['user_id'])) {
header("Location: login.php");
exit();
}

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "user"; 
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}


$user_id = $_SESSION['user_id'];
$name = $_POST['name'];
$email = $_POST['email'];
$frequency = $_POST['frequency'];
$amount = $_POST['amount'];
$method = $_POST['method'];
$message = $_POST['message'];
$date = date('Y-m-d');

if (isset($_POST['selected-children']) && !empty($_POST['selected-children'])) {
$selectedChildren = explode("\n", trim($_POST['selected-children']));
$amount = $_POST['amount']/count($selectedChildren);
foreach ($selectedChildren as $childInfo) {
// Extract child name from the child info
$childName = strtok($childInfo, " (Age:");

// Fetch the child ID based on the name
$child_sql = "SELECT childid FROM children WHERE name = ?";
$stmt = $conn->prepare($child_sql);
$stmt->bind_param("s", $childName);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
$row = $result->fetch_assoc();
$childId = $row['childid'];

// Insert into the donations table for each child
$insert_sql = "INSERT INTO donation (name, email, frequency, amount, date, method, childid) VALUES (?, ?, ?, ?, ?, ?, ?)";
$insert_stmt = $conn->prepare($insert_sql);
$insert_stmt->bind_param("sssissi", $name, $email, $frequency, $amount, $date, $method, $childId);
$insert_stmt->execute();


}
}

echo "Donation successfully recorded for selected children.";
} else {
echo "No children selected.";
}

$conn->close();
?>
