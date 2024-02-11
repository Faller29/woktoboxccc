<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "inventory_system";

$con = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['checkout'])) {
    // Step 1: Fetch the cart items and their quantities from $_SESSION['cart']
    $totalAmount = 0;
    $cartItems = $_SESSION['cart'];

    // Step 2: For each product in the cart, deduct the required quantity of each ingredient from the inventory
    foreach ($cartItems as $item) {
        $productName = $item['product_name'];
        $productPrice = $item['product_price'];
        $quantity = $item['quantity'];
        $totalPrice = $item['total_price'];

        $totalAmount += $totalPrice;

        // Step 2a: Fetch the ingredients required for this product from the product_ingredients table
        $productId = $item['product_id'];
        $sql = "SELECT ingredient_id, quantity_required FROM productingredient WHERE product_id = $productId";
        $result = mysqli_query($con, $sql);

        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $ingredientId = $row['ingredient_id'];
                $quantityRequired = $row['quantity_required'];

                // Step 2b: Deduct the required quantity of each ingredient from the inventory
                $updateSql = "UPDATE ingredients SET ingredient_quantity = ingredient_quantity - ($quantityRequired * $quantity) WHERE ingredient_id = $ingredientId";
                mysqli_query($con, $updateSql);
            }
        }
    }

    // Step 3: Update the cart item into the database table
    foreach ($cartItems as $item) {
        $productName = $item['product_name'];
        $productPrice = $item['product_price'];
        $quantity = $item['quantity'];
        $totalPrice = $item['total_price'];

        // Insert the cart item into the database table, including the current date and time
        $dateAdded = date('Y-m-d H:i:s');
        $sql = "INSERT INTO cart_items (product_name, product_price, quantity, total_price, date_added)
                VALUES ('$productName', $productPrice, $quantity, $totalPrice, '$dateAdded')";
        mysqli_query($con, $sql);
    }

    // Clear the cart after successful checkout
    unset($_SESSION['cart']);

    // Redirect to a success page or back to the cart page
    
    $successMessage = urlencode("Check out Completed!");
    header("Location: display.php?success=true&message=$successMessage");
    exit();
}

mysqli_close($con);
?>

