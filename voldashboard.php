<?php
session_start();

if (!isset($_SESSION['volunteer_id'])) {
header("Location: login.php");
exit();
}

$servername = "localhost";
$username = "root";
$password = NULL;
$dbname = "user";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}

$volunteer_id = $_SESSION['volunteer_id'];

$volunteer_sql = "SELECT * FROM volunteer WHERE Id = $volunteer_id";
$volunteer_result = $conn->query($volunteer_sql);
$volunteer = $volunteer_result->fetch_assoc();

$child_sql = "SELECT * FROM children WHERE volid = $volunteer_id";
$child_result = $conn->query($child_sql);

$progress_sql = "SELECT * FROM progress WHERE volid = $volunteer_id";
$progress_result = $conn->query($progress_sql);
$progress = $progress_result->fetch_assoc();

$conn->close();

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
<title>Volunteer Dashboard - Nurturing Intellects</title>
<link rel="stylesheet" href="styles.css">
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

body {
font-family: 'Poppins', sans-serif;
background-color: #f8f9fa;
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
position: sticky;
top: 0;
z-index: 100;
}

header h1 {
margin: 0;
font-size: 36px;
text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
letter-spacing: 2px;
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

.dashboard {
display: grid;
grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
gap: 40px;
padding: 40px;
}

.card {
background-color: white;
border-radius: 20px;
box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
padding: 40px;
position: relative;
overflow: hidden;
transition: all 0.3s ease-in-out;
}

.card:hover {
transform: translateY(-8px);
box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
}

.card::before {
content: '';
position: absolute;
top: 0;
left: 0;
width: 100%;
height: 8px;
background-color: #6c63ff;
}

.card h2 {
margin-top: 0;
color: #6c63ff;
text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
font-size: 32px;
margin-bottom: 20px;
}

.card p {
color: #555;
font-size: 20px;
line-height: 1.6;
}

.card .status {
font-weight: 600;
color: #ff6b6b;
}

.button {
background-color: #6c63ff;
color: white;
padding: 15px 30px;
border: none;
border-radius: 30px;
cursor: pointer;
font-size: 18px;
font-weight: 500;
transition: all 0.3s ease;
position: absolute;
bottom: 40px;
right: 40px;
}

.button:hover {
background-color: #ff6b6b;
transform: scale(1.05);
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
.child-details {
display: none;
padding: 20px;
background-color: #f8f8f8;
border-radius: 10px;
margin-top: 20px;
}

.child-details h3 {
margin-top: 0;
color: #6c63ff;
font-size: 24px;
margin-bottom: 10px;
}

.child-details p {
color: #555;
font-size: 18px;
margin: 5px 0;
}

/* Added CSS for the "View Child" button and hidden details */
.view-child-button {
background-color: #6c63ff;
color: white;
padding: 10px 20px;
border: none;
border-radius: 5px;
cursor: pointer;
font-size: 16px;
font-weight: 500;
margin-top: 10px;
transition: background-color 0.3s ease;
}

.view-child-button:hover {
background-color: #ff6b6b;
}

.child-details.visible {
display: block;
}
.eventDetails {
display: none; /* Initially hidden */
}
</style>
</head>
<body>
<header>
<h1 class="neon-text">Volunteer Dashboard</h1>
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
<h2 class="three-d-text" data-text="Account Details" style="text-decoration:underline">Account Details -</h2>
<p>Name: <?php echo $volunteer['Name']; ?></p>
<p>Email: <?php echo $volunteer['Email']; ?></p>
<p>Phone: <?php echo $volunteer['Contact']; ?></p>
</div>
<div class="card">
<h2 class="three-d-text" data-text="Children Under Care Details" style="text-decoration:underline">Child Under Care Details -</h2>
<!-- <p>Name: <?php echo $child['name']; ?></p>
<p>Age: <?php echo $child['age']; ?></p>
<p>Address: <?php echo $child['address']; ?></p>
<p>School: <?php echo $child['school']; ?></p>
<p>Funded By: <?php echo $child['fundedby']; ?></p> -->


<button class="view-child-button">View Children</button>

<div class="child-details">
<?php while ($child = $child_result->fetch_assoc()) { ?>
<h3>Child Details-</h3>
<p>Name: <?php echo $child['name']; ?></p>
<p>Age: <?php echo $child['age']; ?></p>
<p>Address: <?php echo $child['address']; ?></p>
<p>School: <?php echo $child['school']; ?></p>
<p>Funded By: <?php echo $child['fundedby']; ?></p>
<?php } ?>
</div>
</div>
</div>
<div class="card">
<h2 class="three-d-text" style="text-decoration:underline">Upcoming Events-</h2>
<p>Join us for the upcoming charity walk on <span class="status">June 20th, 2024</span>.</p>
<button class="button" id="viewEventBtn">View Events</button>

<div class="eventDetails">
<h3>List of Events</h3>
<ul>
<li>**STEAM Fair:** Explore Science, Technology, Engineering, Arts, and Math through fun activities. (Date: Saturday, July 20th, 2024)</li>
<li>**Coding for Kids Workshop:** Learn the basics of coding and unleash creative potential. (Date: Tuesday, July 23rd, 2024)</li>
<li>**Storytelling Extravaganza:** Be captivated by engaging narratives and foster creativity. (Date: Thursday, August 1st, 2024)</li>
<li>**Book Drive & Reading Marathon:** Donate books and participate in reading aloud to promote literacy. (Date: Saturday, August 3rd, 2024)</li>
<li>**Career Exploration Day:** Learn about different career paths and gain valuable insights. (Date: Wednesday, August 7th, 2024)</li>
<li>**College Application Workshop:** Get guidance on essays, scholarships, and financial aid for college. (Date: Saturday, August 10th, 2024)</li>
<li>**Back-to-School Supply Drive:** Donate essential school supplies and help children succeed. (Date: Tuesday, August 13th, 2024)</li>
<li>**Chess Tournament:** Challenge young minds and promote critical thinking skills in a fun environment. (Date: Friday, August 16th, 2024)</li>
<li>**"Science in the Park" Day:** Conduct experiments, explore nature, and learn about science. (Date: Sunday, August 18th, 2024)</li>
<li>**"Meet the Author" Event:** Get inspired by a children's author and their creative journey. (Date: Tuesday, August 20th, 2024)</li>
</ul>
</div>
</div>

</div>
<!-- <div class="card">
<h2 class="three-d-text" data-text="Payment Details">Payment Details</h2>
<p class="status">Payment Status: Due</p>
<button class="button neon-button">Make Payment</button>
</div> 
</div> -->

<footer>
<div class="footer-content">
<h3>About Nurturing Intellects</h3>
<p>Nurturing Intellects is dedicated to improving the lives of underprivileged children through education and community support. Join us in our mission to create brighter futures.</p>
</div>
<div>
<h3>Quick Links</h3>
<ul>
<li ><a href="index.html" style="color:blue">Home</a></li>
<li><a href="school.html">Schools</a></li>
<li><a href="children.html">CHildren</a></li>
<li><a href="donate.html" style="color:blue">Donate</a></li>
</ul>
</div>
<div>
<h3>Newsletter</h3>
<p>Sign up for our newsletter to stay updated:</p>
<form action="#">
<input type="email" placeholder="Enter your email">
<button type="submit">Subscribe</button>
</form>
</div>
</div>
<div class="footer-bottom">
<p>&copy; 2024 Nurturing Intellects. All rights reserved.</p>
</div>
</footer>

<script>
document.addEventListener('DOMContentLoaded', function () {
const buttons = document.querySelectorAll('.view-child-button');

buttons.forEach(button => {
button.addEventListener('click', () => {
const details = button.nextElementSibling;
details.classList.toggle('visible');
});
});
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
const viewEventBtn = document.getElementById('viewEventBtn');
const eventDetails = document.querySelector('.eventDetails');

viewEventBtn.addEventListener('click', function() {
if (eventDetails.style.display === 'none') {
eventDetails.style.display = 'block'; 
} else {
eventDetails.style.display = 'none'; 
}
});
});
</script>
</body>
</html>
