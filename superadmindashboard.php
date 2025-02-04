<?php
session_start();

// Check if the user is logged in as superadmin
if (!isset($_SESSION['superadmin'])) {
header("Location: login.php");
exit();
}

include_once("./conn2.php");

// Fetch all volunteers
$volunteer_sql = "SELECT * FROM volunteer";
$volunteer_result = $conn->query($volunteer_sql);

// Fetch all children and count per volunteer
$child_sql = "SELECT volid, COUNT(*) as child_count FROM children GROUP BY volid";
$child_result = $conn->query($child_sql);

$volunteer_child_count = [];
while ($row = $child_result->fetch_assoc()) {
$volunteer_child_count[$row['volid']] = $row['child_count'];
}
while ($volunteer = $volunteer_result->fetch_assoc()) {
$volunteer_id = $volunteer['Id'];
if (!isset($volunteer_child_count[$volunteer_id])) {
$volunteer_child_count[$volunteer_id] = 0;
}
}
$volunteer_result->data_seek(0);
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add-child'])) {
$child_name = $_POST['child-name'];
$child_age = $_POST['child-age'];
$child_address = $_POST['child-address'];
$child_school = $_POST['child-school'];
$child_fund = $_POST['child-fund'];

asort($volunteer_child_count);  // Sort volunteers by child count
$volunteer_with_least_children = key($volunteer_child_count);  // Get the volunteer ID with the least children


// Insert the new child into the children table
$insert_child_sql = "INSERT INTO children (name, age,address, school, volid,fundedby) VALUES ('$child_name', '$child_age','$child_address' ,'$child_school', '$volunteer_with_least_children','$child_fund')";
if ($conn->query($insert_child_sql) === TRUE) {
echo "New child assigned successfully!";
// Update the child count for the assigned volunteer
$volunteer_child_count[$volunteer_with_least_children]++;
} else {
echo "Error: " . $insert_child_sql . "<br>" . $conn->error;
}
$child_sql = "SELECT volid, COUNT(*) as child_count FROM children GROUP BY volid";
$child_result = $conn->query($child_sql);

$volunteer_child_count = [];
while ($row = $child_result->fetch_assoc()) {
$volunteer_child_count[$row['volid']] = $row['child_count'];
}
$volunteer_result->data_seek(0);
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout'])) {
  session_destroy();
  header("Location: index.html");
  exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Superadmin Dashboard</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<style>
body {
font-family: 'Poppins', sans-serif;
background-color: #f0f0f5;
color: #333;
margin: 0;
padding: 0;
}

header {
background-color: #6c63ff;
color: white;
padding: 30px 0;
text-align: center;
box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
position: relative;
top: 0;
z-index: 100;
}

header h1 {
margin: 0;
font-size: 36px;
text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
letter-spacing: 2px;
}
.logout-button {
    position: absolute;
    top: 20px;
    right: 20px;
    background-color: #ff6b6b;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 30px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.logout-button:hover {
    background-color: #6c63ff;
    transform: scale(1.05);
}

nav {
margin-top: 20px;
}

nav ul {
list-style-type: none;
padding: 0;
display: flex;
justify-content: center;
margin: 0;
}

nav ul li {
margin: 0 25px;
}

nav ul li a {
text-decoration: none;
color: white;
font-weight: 500;
font-size: 18px;
transition: all 0.3s ease;
padding: 10px 20px;
border-radius: 30px;
}

nav ul li a:hover {
background-color: #ff6b6b;
color: white;
transform: translateY(-3px);
box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
}

.dashboard {
display: flex;
flex-direction: column;
align-items: center;
margin: 40px 20px;
}

.card {
background-color: bisque;
border-radius: 10px;
box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
margin: 20px;
padding: 30px;
width: 80%;
max-width: 800px;
transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}

.card:hover {
transform: scale(1.05);
box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
}

.card h2 {
margin-top: 0;
color: #3a6073;
font-size: 24px;
text-align: center;
text-transform: uppercase;
letter-spacing: 2px;
position: relative;
padding-bottom: 10px;
}

.card h2::after {
content: "";
position: absolute;
bottom: 0;
left: 50%;
transform: translateX(-50%);
width: 80px;
height: 3px;
background-color: #3a7bd5;
}

.card p {
color: #555;
line-height: 1.6;
}

.card form {
display: flex;
flex-direction: column;
}

.card label {
font-weight: bold;
margin-top: 10px;
}

.card input,
.card select,
.card textarea {
padding: 10px;
margin-top: 5px;
border-radius: 5px;
border: 1px solid #ddd;
transition: border-color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
}

.card input:focus,
.card select:focus,
.card textarea:focus {
border-color: #3a7bd5;
box-shadow: 0 0 8px rgba(58, 123, 213, 0.3);
}

.card button {
background-color: #3a7bd5;
color: white;
cursor: pointer;
transition: background-color 0.3s ease-in-out, transform 0.3s ease-in-out;
padding: 10px 20px;
border: none;
border-radius: 5px;
font-size: 16px;
font-weight: 500;
margin-top: 10px;
}

.card button:hover {
background-color: #3a6073;
transform: scale(1.05);
}

.table-container {
overflow-x: auto;
}

table {
width: 100%;
border-collapse: collapse;
margin: 20px 0;
box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
border-radius: 10px;
overflow: hidden;
}

th,
td {
padding: 15px;
border: none;
text-align: left;
transition: background-color 0.3s ease-in-out;
}

th {
background-color: #3a7bd5;
color: white;
font-weight: 600;
text-transform: uppercase;
letter-spacing: 1px;
}

tr:nth-child(even) {
background-color: #f8f8f8;
}

tr:hover {
background-color: #f0f0f5;
}
footer {
background-color: #333;
color: white;
text-align: center;
padding: 40px 0;
margin-top: 60px;
}

.footer-content {
display: flex;
justify-content: space-around;
flex-wrap: wrap;
}

.footer-content div {
flex: 1;
max-width: 300px;
margin-bottom: 40px;
}

.footer-content h3 {
color: #6c63ff;
font-size: 24px;
margin-bottom: 20px;
}

.footer-content p,
.footer-content ul {
font-size: 18px;
line-height: 1.6;
}

.footer-content ul {
list-style-type: none;
padding: 0;
}

.footer-content ul li {
margin-bottom: 15px;
}

.footer-content ul li a {
color: white;
text-decoration: none;
transition: color 0.3s ease;
}

.footer-content ul li a:hover {
color: #ff6b6b;
}

.footer-content form input,
.footer-content form textarea {
width: 100%;
padding: 15px;
margin-bottom: 15px;
border: none;
border-radius: 30px;
font-size: 16px;
background-color: rgba(255, 255, 255, 0.1);
color: white;
}

.footer-content form input::placeholder,
.footer-content form textarea::placeholder {
color: #ccc;
}

.footer-content form button {
background-color: #ff6b6b;
color: white;
padding: 15px 30px;
border: none;
border-radius: 30px;
cursor: pointer;
font-size: 18px;
font-weight: 500;
transition: all 0.3s ease;
}

.footer-content form button:hover {
background-color: #6c63ff;
transform: scale(1.05);
}

.footer-bottom {
margin-top: 30px;
font-size: 16px;
}

@media screen and (max-width: 768px) {
header {
padding: 20px 0;
}

header h1 {
font-size: 28px;
}

nav ul li {
margin: 0 15px;
}

nav ul li a {
font-size: 16px;
padding: 8px 16px;
}

.dashboard {
padding: 30px;
gap: 30px;
}

.card {
padding: 30px;
border-radius: 15px;
}

.card h2 {
font-size: 28px;
margin-bottom: 15px;
}

.card p {
font-size: 18px;
}

.button {
font-size: 16px;
padding: 12px 24px;
bottom: 30px;
right: 30px;
}

footer {
padding: 30px 0;
margin-top: 40px;
}

.footer-content div {
margin-bottom: 30px;
}

.footer-content h3 {
font-size: 22px;
margin-bottom: 15px;
}

.footer-content p,
.footer-content ul {
font-size: 16px;
}

.footer-content ul li {
margin-bottom: 10px;
}

.footer-content form input,
.footer-content form textarea {
padding: 12px;
margin-bottom: 12px;
font-size: 14px;
}

.footer-content form button {
padding: 12px 24px;
font-size: 16px;
}

.footer-bottom {
margin-top: 20px;
font-size: 14px;
}
}


</style>
</head>
<body>
<header>
<h1 style="text-decoration:underline">Superadmin Dashboard</h1>
<nav>
<ul>
<li><a href="index.html">Home</a></li>
<li><a href="school.html">Schools</a></li>
<li><a href="children.html">Children</a></li>
<li><a href="donate.html">Donate</a></li>

</ul>
</nav>
<form method="post">
    <button type="submit" name="logout" class="logout-button">Logout</button>
</form>
</header>

<div class="dashboard">
<div class="card">
<h2 style="color:black">Volunteers and Associated Children</h2>
<div class="table-container">
<table>
<thead>
<tr>
<th>Volunteer Name</th>
<th>Email</th>
<th>Number of Children</th>
</tr>
</thead>
<tbody>
<?php while ($volunteer = $volunteer_result->fetch_assoc()) { 
$volunteer_id = $volunteer['Id'];
$child_count = isset($volunteer_child_count[$volunteer_id]) ? $volunteer_child_count[$volunteer_id] : 0;
?>
<tr>
<td><?php echo $volunteer['Name']; ?></td>
<td><?php echo $volunteer['Email']; ?></td>
<td><?php echo $child_count; ?></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>

<div class="card">
<h2 style="color:black">Add New Child</h2>
<form action="superadmindashboard.php" method="post">
<label for="child-name">Child Name:</label>
<input type="text" id="child-name" name="child-name" required>
<label for="child-age">Child Age:</label>
<input type="number" id="child-age" name="child-age" required>
<label for="child-address">Child Address:</label>
<input type="text" id="child-address" name="child-address" required>
<label for="child-school">Child School:</label>
<input type="text" id="child-school" name="child-school" required>
<label for="child-fund">Funded By:</label>
<input type="text" id="child-fund" name="child-fund" required>
<button type="submit" class="btn" name="add-child">Add Child</button>
</form>
</div>
</div>

<footer>
<div class="footer-content">
<div>
<h3>About Us</h3>
<p>Nurturing Intellects is dedicated to improving the lives of underprivileged children through education and community support.</p>
</div>
<div>
<h3>Quick Links</h3>
<ul>
<li><a href="index.html">Home</a></li>
<li><a href="school.html">Schools</a></li>
<li><a href="children.html">Children</a></li>
<li><a href="donate.html">Donate</a></li>
</ul>
</div>
<div>
<h3>Contact Us</h3>
<form action="contact.html" method="post">
<input type="email" name="email" placeholder="Your Email" required>
<textarea name="message" rows="4" placeholder="Your Message" required></textarea>
<br>
<br>
<button type="submit" class="button neon-button">Send</button>
</form>
</div>
</div>
<div class="footer-bottom">
<p>&copy; 2024 Nurturing Intellects. All rights reserved.</p>
</div>
</footer>
</footer>

</body>
</html>

<?php
$conn->close();
?>
