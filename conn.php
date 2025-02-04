<?php
$host="localhost";
$username="root";
$password=NULL;
$database="user";

//using php data object class
try{
$conn=new PDO("mysql:host=$host;dbname=$database",$username,$password);
$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
echo "Connected";}
catch(PDOEXCEPTION $err){
  echo"Connection failed";
}
?>
