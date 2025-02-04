<?php
$host="localhost";
$username="root";
$password=NULL;
$database="user";
//using mysqli class
$conn=new mysqli($host,$username,$password,$database);
if($conn->connect_error){
  die("connection failes");
}
echo "";
// echo"<br>";
// $result=$conn->query('show tables')->fetch_all();
// print_r($result);//since result is array
?>
