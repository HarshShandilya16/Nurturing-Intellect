<?php
session_start();
        if(isset($_POST['button'])){
          $user=$_POST['user'];
          $pass=$_POST['pass'];
          //superadmin
          $superadminemail="harshshandilya101@gmail.com";
          $superadminpassword="Harsh@4814";
         if($user==$superadminemail && $pass==$superadminpassword){
          $_SESSION['superadmin']=true;
          header("Location: superadmindashboard.php");
          exit();
}else{


          include_once("./conn2.php");
          $query="select * from volunteer where Email='$user' and Password='$pass'";
          $result=mysqli_query($conn,$query);
          if($result && $result->num_rows>0){
            $row=$result->fetch_assoc();
            $_SESSION['volunteer_id']=$row['Id'];
            header("Location: voldashboard.php");
            exit();
          }
            else{
              echo "<br>";
              echo "fail";
            }
          }
        }
        ?>