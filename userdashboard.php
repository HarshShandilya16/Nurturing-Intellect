<?php
session_start();

if (!isset($_SESSION['user_id'])) {
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

$user_id = $_SESSION['user_id'];

$user_sql = "SELECT * FROM login WHERE Id = $user_id";
$user_result = $conn->query($user_sql);
$user = $user_result->fetch_assoc();

$child_sql = "SELECT * FROM children WHERE userid = $user_id";
$child_result = $conn->query($child_sql);
$child_result1 = $conn->query($child_sql);

$progress_data = [];
while ($childprogress = $child_result1->fetch_assoc()) {
$child_id = $childprogress['childid'];
$progress_sql = "SELECT * FROM progress WHERE childid = $child_id";
$progress_result = $conn->query($progress_sql);
$progress_data[$child_id] = [];
while ($progress = $progress_result->fetch_assoc()) {
    $progress_data[$child_id][] = $progress;
}
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
<title>User Dashboard - Nurturing Intellects</title>
<link rel="stylesheet" href="userdashboard.css"> 
<style>

</style>
</head>
<body>
<header>
<h1 class="neon-text">User Dashboard</h1>
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
<p>First-Name: <?php echo $user['firstname']; ?></p>
<p>Last-Name: <?php echo $user['lastname']; ?></p>
<p>Email: <?php echo $user['email']; ?></p>
<p>Phone: <?php echo $user['contact']; ?></p>
</div>
<div class="card">
<h2 class="three-d-text" data-text="Children Under Care Details" style="text-decoration:underline">Child Details -</h2>
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
<button class="view-progress-button">View Progress</button>
<div class="progress-details">
<?php if (isset($progress_data[$child['childid']])){
foreach ($progress_data[$child['childid']] as $progress) { ?>
<h4>Progress Report -</h4>
<p>Academics: <?php echo $progress['Academics']; ?></p>
<p>Sports: <?php echo $progress['Sports']; ?></p>
<p>Cocurricular: <?php echo $progress['Cocurricular']; ?></p>
<p>Personality: <?php echo $progress['Personality']; ?></p>
<?php } 
}else{?>
<p>No progress data available.</p>
<?php } ?>
</div>

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
<div class="card">
<h2>Donation</h2>
<button id="selectchild" class="button">Select children</button>
<p>Select children for donation:</p>
<form id="children-selection-form" action="donate.php" method="post">
<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
<div class="children-grid">
<?php
$children_sql = "SELECT * FROM children WHERE userid = 0";
$children_result = $conn->query($children_sql);

if ($children_result->num_rows > 0) {
while ($row = $children_result->fetch_assoc()) {
echo '<div class="child-card">';
echo '<div class="checkbox-wrapper">';
echo '<input type="checkbox" id="child-' . $row['childid'] . '" name="children[]" value="' . $row['childid'] . '">';
echo '<label for="child-' . $row['childid'] . '" class="checkbox-label"></label>';
echo '</div>';
echo '<div class="child-info">';
echo '<h3>' . $row['name'] . '</h3>';
echo '<p><strong>Age:</strong> ' . $row['age'] . '</p>';
echo '<p><strong>Address:</strong> ' . $row['address'] . '</p>';
echo '<p><strong>School:</strong> ' . $row['school'] . '</p>';
echo '</div>';
echo '</div>';
}
} else {
echo '<p>No children available for donation at this time.</p>';
}

echo'<br>';
echo'<br>';
?>
<p>For further information about the adopted child, you can check <a href="children.html"><strong>Here</strong></a>.</p>
<button type="submit" id="proceedToPayment" class="button">Proceed to payment</button>
</form>
</div>
<script>
document.getElementById('proceedToPayment').addEventListener('click', function() {
    window.location.href = 'donate.html';
});
</script>


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
const progressButtons = document.querySelectorAll('.view-progress-button');

buttons.forEach(button => {
button.addEventListener('click', () => {
const details = button.nextElementSibling;
details.classList.toggle('visible');
});
});
progressButtons.forEach(button => {
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

</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
const viewchildrenBtn = document.getElementById('selectchild');
const childrenDetails = document.querySelector('.children-grid');

viewchildrenBtn.addEventListener('click', function() {
if (childrenDetails.style.display === 'none') {
childrenDetails.style.display = 'block'; 
} else {
childrenDetails.style.display = 'none'; 
}
});
});
</script>
</body>
</html>
