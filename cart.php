<?php
session_start();

if (isset($_GET['remove'])) {
    $key = $_GET['remove'];
    if (isset($_SESSION['cart'][$key])) {
        unset($_SESSION['cart'][$key]);
        $successMessage = urlencode("Product removed from the cart successfully!");
    }
}

if (isset($_POST['product_id']) && isset($_POST['quantity'])) {
    $productID = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "inventory_system";
    $con = mysqli_connect($servername, $username, $password, $database);

    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Fetch product details from the database
    $sql = "SELECT * FROM product WHERE product_id = '$productID'";
    $result = mysqli_query($con, $sql);
    $product = mysqli_fetch_assoc($result);

    if ($quantity == 0) {
        $successMessage = urlencode("Invalid quantity!");
        header("Location: display.php?success=false&message=$successMessage");
        exit();
    }

    if ($product) {
        $productName = $product['product_name'];
        $productPrice = $product['product_price'];
        $totalPrice = $productPrice * $quantity;

        // Check if there is enough inventory for this product
        $hasEnoughInventory = true;
        $ingredientSql = "SELECT ingredient_id, quantity_required FROM productingredient WHERE product_id = $productID";
        $ingredientResult = mysqli_query($con, $ingredientSql);

        if ($ingredientResult) {
            while ($ingredientRow = mysqli_fetch_assoc($ingredientResult)) {
                $ingredientId = $ingredientRow['ingredient_id'];
                $quantityRequired = $ingredientRow['quantity_required'];

                $inventorySql = "SELECT ingredient_quantity FROM ingredients WHERE ingredient_id = $ingredientId";
                $inventoryResult = mysqli_query($con, $inventorySql);

                if ($inventoryResult) {
                    $inventoryRow = mysqli_fetch_assoc($inventoryResult);
                    $availableQuantity = $inventoryRow['ingredient_quantity'];
                    if (($quantityRequired * $quantity) > $availableQuantity) {
                        // Not enough inventory for this ingredient
                        $hasEnoughInventory = false;
                        break;
                    }
                }
            }
        }

        if (!$hasEnoughInventory) {
            // If there is not enough inventory for the product, show a warning message

            $successMessage = urlencode("Low ingredient for '$productName'. Only 1 serving can be cooked.");
            header("Location: display.php?success=false&message=$successMessage");
            exit();
        }

        // Create a new item for the cart
        $item = [
            'product_id' => $productID,
            'product_name' => $productName,
            'product_price' => $productPrice,
            'quantity' => $quantity,
            'total_price' => $totalPrice
        ];

        // Check if the cart exists in the session
        if (isset($_SESSION['cart'])) {
            // Add the new item to the existing cart
            $_SESSION['cart'][] = $item;

            $successMessage = urlencode("Product added to cart successfully!");
        } else {
            // Create a new cart and add the item
            $_SESSION['cart'] = array($item);

            $successMessage = urlencode("Product added to cart successfully!");
        }

    }

    mysqli_close($con);
}

// Redirect back to the index page
header("Location: display.php?success=true&message=$successMessage");
exit();
?>