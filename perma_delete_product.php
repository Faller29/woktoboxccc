<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "inventory_system";
$con = mysqli_connect($servername, $username, $password, $database);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['product_id'])) {
    $productId = $_POST['product_id'];

    // Delete the selected product from the "archived_product" table
    $sqlDeleteProduct = "DELETE FROM product WHERE product_id = '$productId'";
    $resultDeleteProduct = mysqli_query($con, $sqlDeleteProduct);

    if ($resultDeleteProduct) {
        $successMessage = urlencode("Product deleted successfully!");
        header("Location: archived_product.php?success=true&message=$successMessage");
    } else {
        $warningMessage = urlencode("Product deletion failed with warning");
        header("Location: archived_product.php?success=false&warning=$warningMessage");
    }
}

// Close the database connection
mysqli_close($con);
?>
