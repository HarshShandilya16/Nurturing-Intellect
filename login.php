<?php
if(isset($_POST['button'])){
    $firstname=$_POST['firstname'];
    $lastname=$_POST['lastname'];
    $date=$_POST['date'];
    $number=$_POST['number'];
    $email=$_POST['email'];
    $user=$_POST['user'];
    $pass=$_POST['pass'];
    include_once("./conn.php");
    $student=$conn->prepare("INSERT INTO `login`(`firstname`,`lastname`,`date`,`contact`,`email`,`username`,`password`)
Values('$firstname','$lastname','$date','$number','$email','$user','$pass')
");
 $result=$student->execute();
 if($result){
echo "<br>";
  echo "success";

 }
 else{
    echo "<script type='text/javascript> alert('Please Enter Valid Information')</script>";
 }
}
?>