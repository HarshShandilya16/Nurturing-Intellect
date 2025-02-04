<?php
session_start();
        if(isset($_POST['button'])){
          $user=$_POST['user'];
          $pass=$_POST['pass'];
          include_once("./conn2.php");
          $query="select * from login where username='$user' and password='$pass'";
          $result=mysqli_query($conn,$query);
          if($result && $result->num_rows>0){
            $row=$result->fetch_assoc();
            $_SESSION['user_id']=$row['Id'];
            header("Location: userdashboard.php");
            exit();
          }
            else{
              echo "<br>";
              echo "fail";
            }
          }
          