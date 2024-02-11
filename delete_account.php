<?php
 $servername = "localhost";
 $username="root";
 $password="";
 $database="inventory_system";
 
 $con = mysqli_connect($servername,$username,$password,$database);

 if(isset($_GET['deleteid'])){

    $id=$_GET['deleteid'];

    $sql="delete from `accounts` where id=$id";

    $result=mysqli_query($con,$sql);

    if($result){
       // echo "Deleted Successfully!";

       $successMessage = urlencode("Account deleted successfully!");
       header("Location: account.php?success=true&message=$successMessage");

    }
    else{
      $warningMessage = urlencode("Account deletion failed");
      header("Location: account.php?success=false&warning=$warningMessage");

        die(mysqli_error($con));

    }

 }

 ?>