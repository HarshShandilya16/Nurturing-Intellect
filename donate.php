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
 if (isset($_POST['children']) && !empty($_POST['children'])) {
            $selectedChildren = $_POST['children'];
            $childrenInfo = [];
            foreach ($selectedChildren as $childId) {
                $update_sql = "UPDATE children SET userid =$user_id WHERE childid = $childId";
                $update_result = $conn->query($update_sql);
            }
        }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate - Nurturing Intellects</title>
    <link rel="stylesheet" href="donate.css">
    <style>
        #selected-children {
            font-size: 1.3em;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
          <img src="aaron-burden-6jYoil2GhVk-unsplash.jpg" class="image" alt="Nurturing Intellects">
        </div>
        <div class="text">
          <h1>Nurturing Intellects</h1>
          </div>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="school.html">Schools</a></li>
                <li><a href="children.html">Children</a></li>
                <li><a href="volunteer.html">Volunteer</a></li>
                
                
            </ul>
        </nav>
    </header>
    

<section id="donate" class="section">
<div class="container">
<h2 strong style="text-decoration: underline;">Support Our Cause</h2>
<p>Your donation can make a significant impact in the lives of children around the country. By donating to Nurturing Intellects, you are contributing to education and community support initiatives that transform lives.Your contribution enables us to provide essential educational resources, such as books, school supplies, and learning materials, to underprivileged communities. Moreover, we support the construction and renovation of classrooms, ensuring that children have a conducive learning environment.
Our educational initiatives extend beyond the classroom, as we also offer after-school programs, tutoring services, and vocational training opportunities. These programs equip children with valuable skills and knowledge, empowering them to pursue their dreams and break the cycle of poverty. Additionally, your donation helps us facilitate admission for talented students from underprivileged backgrounds into prestigious private schools, where they can receive a top-notch education and unlock their full potential.
By investing in education, you are not only shaping young minds but also paving the way for a brighter future for entire communities.</p>

<h3>Why Donate?</h3>
<p>Your support helps us provide essential resources to underprivileged children. Here are some ways your donation makes a difference:</p>
<ul>
<li><strong strong style="text-decoration: underline;">250 Rs:</strong> Provides a school uniform and supplies for a child.</li>
<li><strong strong style="text-decoration: underline;">500 Rs:</strong> Covers the cost of Guidance and Mentoring sessions for the underprivileged</li>
<li><strong strong style="text-decoration: underline;">1000 Rs:</strong> Supports one month's education for a child.</li>

</ul>

<h3>Ways to Donate</h3>
<ul>
<li><strong style="text-decoration: underline;">One-Time Donation:</strong> Make a one-time contribution to support our ongoing projects and initiatives.</li>
<li><strong strong style="text-decoration: underline;">Monthly Giving:</strong> Become a monthly donor and provide consistent support to our mission.</li>
<li><strong strong style="text-decoration: underline;">In-Kind Donations:</strong> Donate goods or services that can benefit our programs and operations.</li>
<li><strong strong style="text-decoration: underline;">Corporate Sponsorship:</strong> Partner with us through corporate sponsorship and make a large-scale impact.</li>
<li><strong strong style="text-decoration: underline;">Fundraising Events:</strong> Participate in or organize fundraising events to support Nurturing Intellects.</li>
</ul>
<main></main>
<h3>Make a Donation</h3>
<form id="donation-form" action="donate1.php" method="post">
<label for="donor-name">Name:</label>
<input type="text" id="donor-name" name="name" required>

<label for="donor-email">Email:</label>
<input type="email" id="donor-email" name="email" required>

<label for="donation-frequency">Payment Frequency:</label>
<select id="donation-frequency" name="frequency" required>
<option value="1-month">1 Month</option>
<option value="3-months">3 months</option>
<option value="6-months">6 months</option>
<option value="1-year">1 year</option>
</select>

<label for="donation-amount">Donation Amount:</label>
<input type="number" id="donation-amount" name="amount" required>

<label for="donation-method">Payment Method:</label>
<select id="donation-method" name="method" required>
<option value="credit-card">Credit Card</option>
<option value="paypal">PayPal</option>
<option value="bank-transfer">Bank Transfer</option>
</select>
<label for="selected-children">Selected Children:</label>
    <textarea id="selected-children" name="selected-children" rows="4" readonly><?php
        if (isset($_POST['children']) && !empty($_POST['children'])) {
            $selectedChildren = $_POST['children'];
            $childrenInfo = [];
            foreach ($selectedChildren as $childId) {
                $child_sql = "SELECT name, age FROM children WHERE childid = $childId";
                $child_result = $conn->query($child_sql);
                if ($child_result->num_rows > 0) {
                    while ($row = $child_result->fetch_assoc()) {
                        $childrenInfo[] = $row['name'] . " (Age: " . $row['age'] . ")";
                    }
                }
            }
            echo implode("\n", $childrenInfo);
           
        }
    ?></textarea>


<label for="donation-message">Message:</label>
<textarea id="donation-message" name="message" rows="4" placeholder="Optional message"></textarea>

<button type="submit" class="btn" name="button">Donate Now</button>
</form>
</main>
<script>
document.getElementById('donation-frequency').addEventListener('change', function() {
var amountInput = document.getElementById('donation-amount');
var frequency = this.value;
var numChildren = <?php echo isset($selectedChildren) ? count($selectedChildren) : 0; ?>;


switch (frequency) {
case '1-month':
amountInput.value = 600*numChildren ;
break;
case '3-months':
amountInput.value = 1000*numChildren ;
break;
case '6-months':
amountInput.value = 2000*numChildren ;
break;
case '1-year':
amountInput.value = 3000*numChildren ;
break;
default:
amountInput.value = '';
}
});
</script>
            

<h3>Donor Recognition</h3>
<p>We are grateful for the generous support of our donors. Your contributions are acknowledged in the following ways:</p>
<ul>
<li><strong>Recognition on Our Website:</strong> All donors are listed on our website.</li>
<li><strong>Thank You Letters:</strong> Personalized thank you letters for significant donations.</li>
<li><strong>Exclusive Updates:</strong> Regular updates on our projects and impact stories.</li>
<li><strong>Event Invitations:</strong> Invitations to special events and galas.</li>
</ul>

<h3>Tax Benefits</h3>
<p>Your donations to Nurturing Intellects are tax-deductible. We provide official receipts for all contributions, which can be used for tax purposes. For more information on tax benefits, please consult with your tax advisor or contact us directly.</p>
</div>
</section>

<footer>
<div class="container">
<div class="footer-content">
<div class="footer-section about">
<h3>About Nurturing Intellects</h3>
<p>Nurturing Intellects is dedicated to improving the lives of underprivileged children through education, healthcare, and community support. Join us in our mission to create brighter futures.</p>
</div>
<div class="footer-section links">
<h3>Quick Links</h3>
<ul>
<li><a href="index.html" style="color: blue;">Home</a></li>
<li><a href="school.html">Schools</a></li>
<li><a href="children.html">Children</a></li>
<li><a href="volunteer.html">Volunteer</a></li>

</ul>
</div>
<div class="footer-section contact-form">
<h3>Contact Us</h3>
<form action="contact.html" method="post">
<input type="email" name="email" placeholder="Your Email" required>
<textarea name="message" rows="4" placeholder="Your Message" required></textarea>
<button type="submit" class="btn">Send</button>
</form>
</div>
</div>
<div class="footer-bottom">
<p>&copy; 2024 Nurturing Intellects. All rights reserved.</p>
</div>
</div>
</footer>

    <script src="scripts.js"></script>
</body>
</html>
