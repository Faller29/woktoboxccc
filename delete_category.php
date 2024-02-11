<?php
 $servername = "localhost";
 $username="root";
 $password="";
 $database="inventory_system";
 
 $con = mysqli_connect($servername,$username,$password,$database);

 if(isset($_GET['deleteid'])){

    $id=$_GET['deleteid'];

    $sql="delete from `ingredient_category` where category_id=$id";

    $result=mysqli_query($con,$sql);

    if($result){
       // echo "Deleted Successfully!";

       $successMessage = urlencode("Category Deleted successfully!");
       header("Location: category.php?success=true&message=$successMessage");

    }
    else{
        die(mysqli_error($con));

    }

 }

 ?>