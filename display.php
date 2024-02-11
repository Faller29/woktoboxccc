<?php
session_start();

$userRole = $_SESSION['userRole'];
include 'get_product.php';

$servername = "localhost";
$username = "root";
$password = "";
$database = "inventory_system";
$con = mysqli_connect($servername, $username, $password, $database);

if (isset($_SESSION['userRole'])) {
  $userRole = $_SESSION['userRole'];
} else {
  echo 'No role';
}


//basically the LOG OUT, where we set them free :<
if (isset($_POST['logout'])) {
  unset($_SESSION['name']);
  unset($_SESSION['username']);
  unset($_SESSION['role']);
  unset($_SESSION['email']);
  unset($_SESSION['mobile']);
  unset($_SESSION['password']);
  session_destroy();
  header('location: login.php');
}


// Fetch products from the database
$sql = "SELECT * FROM product";
$result = mysqli_query($con, $sql);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Catalog</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
        integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">


    <style>
    /* Styles for drop-up container */
    body {
        position: relative;
        min-height: 100vh;
        /* Set the minimum height of the body to at least 100% of the viewport height */
        margin: 0;
        padding-bottom: 60px;
        /* Adjust this value as per your footer height */
    }

    .custom-sticky-dropup {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 999;
    }

    .custom-dropup {
        position: relative;
        display: inline-block;
    }

    .custom-dropdown-menu {
        top: auto;
        bottom: 100%;
        left: 0;
        right: auto;
        transform: translate(0, -10px);
        width: 300px;
        height: 700px;
        overflow: auto;
        position: absolute;
        display: none;
        background-color: #fff;
        border: 1px solid rgba(0, 0, 0, 0.15);
        border-radius: 4px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        z-index: 1000;
    }


    .custom-dropup.open .custom-dropdown-menu {
        display: block;
    }

    /* Additional styling for drop-up */
    .custom-dropup .custom-dropdown-toggle {
        display: inline-block;
        padding: 8px 12px;
        font-size: 120%;
        font-weight: bold;
        line-height: 1;
        color: #fff;
        background-color: #007bff;
        border-radius: 4px;
        border: none;
        cursor: pointer;
    }

    .custom-dropup .custom-dropdown-toggle:focus,
    .custom-dropup .custom-dropdown-toggle:hover {
        background-color: #0056b3;
    }

    .custom-dropup .custom-dropdown-menu {
        max-height: 700px;
        overflow-y: auto;
        padding: 8px;
    }

    .custom-dropup .custom-dropdown-menu a {
        display: block;
        padding: 4px 0;
        color: #333;
        text-decoration: none;
    }

    .custom-dropup .custom-dropdown-menu a:hover {
        background-color: #f5f5f5;
    }

    /* Styles for cart icon */
    .custom-dropup .custom-dropdown-toggle i {
        margin-right: 5px;
        font-size: 100%;
    }

    .custom-input-group {
        z-index: 0;
    }

    .navbar-logo {
        display: flex;
        align-items: center;
    }

    .logo-img {
        height: 50px;
        width: auto;
        /* This will scale the logo proportionally based on the height */
    }

    .product-icons {
        text-align: right;
        font-size: 20px;
    }

    .fa-trash {
        color: red;
    }

    /* Add this CSS to your existing styles or style section */
    .image-display-box {
        margin: 20px auto;
        width: 400px;
        height: 200px;
        border: 1px solid #ccc;
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .product-image-display {
        width: 400px;
        height: 200px;
        object-fit: cover;
    }

    @media (max-width: 640px) {

        .custom-dropdown-menu {
            height: 500px;
        }
    }

    .custom-sticky-dropup {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 999;
    }

    .custom-dropup {
        position: relative;
        display: inline-block;
    }

    .custom-dropdown-menu {
        top: auto;
        bottom: 100%;
        left: 0;
        right: auto;
        transform: translate(0, -10px);
        width: 300px;
        height: 700px;
        overflow: auto;
        position: absolute;
        display: none;
        background-color: #fff;
        border: 1px solid rgba(0, 0, 0, 0.15);
        border-radius: 4px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        z-index: 1000;
    }

    .custom-dropup.open .custom-dropdown-menu {
        display: block;
    }

    .custom-dropup .custom-dropdown-toggle {
        display: inline-block;
        padding: 8px 12px;
        font-size: 120%;
        font-weight: bold;
        line-height: 1;
        color: #fff;
        background-color: #007bff;
        border-radius: 4px;
        border: none;
        cursor: pointer;
    }

    .custom-dropup .custom-dropdown-toggle:focus,
    .custom-dropup .custom-dropdown-toggle:hover {
        background-color: #0056b3;
    }

    .custom-dropup .custom-dropdown-menu {
        max-height: 700px;
        overflow-y: auto;
        padding: 8px;
    }

    .custom-dropup .custom-dropdown-menu a {
        display: block;
        padding: 4px 0;
        color: #333;
        text-decoration: none;
    }

    .custom-dropup .custom-dropdown-menu a:hover {
        background-color: #f5f5f5;
    }

    /* Styles for cart icon */
    .custom-dropup .custom-dropdown-toggle i {
        margin-right: 5px;
        font-size: 100%;
    }

    @media (max-width: 640px) {
        .custom-dropdown-menu {
            height: 500px;
        }
    }

    .action-icon-space {
        margin-left: 40%;
    }

    /* Style for the out-of-stock state */
    .product.out-of-stock {
        position: relative;
        opacity: 0.7;
    }

    /* Overlay text for out-of-stock products */
    .product.out-of-stock::before {
        content: "Out of Stock";
        position: absolute;
        width: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-33%, -100%);
        background-color: rgba(0, 0, 0, .9);
        color: #fff;
        scale: 1.5;
        padding: 6px 12px;
        border-radius: 5px;
        z-index: 100000;
    }


    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-dialog {
        margin: 10% auto;
        margin-top: 100px;
        width: 70%;
        max-width: 500px;
        background-color: #fff;
        border: 1px solid #888;
        border-radius: 5px;
        box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.3);
    }

    .modal-header {
        padding: 15px;
        background-color: #f2f2f2;
    }

    .modal-title {
        margin: 0;
    }

    .close {
        float: right;
        font-size: 24px;
        font-weight: bold;
        cursor: pointer;
    }

    .modal-body {
        padding: 15px;
    }

    .modal-footer {
        padding: 15px;
        background-color: #f2f2f2;
        text-align: right;
    }

    /* Button styles */
    .btn {
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        background-color: #ff0000;
        color: #fff;
    }

    .btn:hover {
        background-color: #feccc9;
        color: #ff0000;
    }

    .btn-secondary {
        background-color: #ccc;
    }

    .btn-danger {
        background-color: #f44336;
        color: #fff;
    }

    .btn-danger:hover {
        background-color: #d32f2f;
    }

    /* Custom input group styles */
    .custom-input-group {
        display: inline-flex;
        align-items: center;
    }

    .custom-input-group .btn {
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
    }

    .custom-input-group .btn:hover {
        background-color: #f1f1f1;
    }

    .custom-input-group .quantity-input {
        padding: 0.375rem 0.75rem;
        text-align: center;
    }

    body {
        position: relative;
        min-height: 100vh;
        margin: 0;
        padding-bottom: 100px;
    }


    .footer {
        position: absolute;
        bottom: 0;
        width: 100%;
        background-color: #C72225;
        color: #FFFFFF;
        text-align: center;
        padding: 0px 0px;
    }

    /* navigation bar */
    .navbar {
        background-image: linear-gradient(45deg, #b30006, #240001);
    }

    /* Info, Edit, Delete Button */
    .product-item i {
        color: #ff0000;
    }

    /*image */
    .product-item:hover img {
        transform: scale(1.05);
        transition: all 0.8s;
    }

    /*product item hover */
    .product-item:hover .btn {
        background-color: #fff;
        color: #ff0000;
        border: 0.1px solid #000;
    }

    /*footer color */
    .footer {
        background-image: linear-gradient(45deg, #b30006, #240001);
    }

    /* "Cart" Button */
    .custom-dropup .custom-btn {
        background-color: #ff0000;
        color: #fff;
    }

    .custom-dropup:hover .custom-btn {
        background-color: #feccc9;
        color: #ff0000;
    }


    .custom-dropup .dropdown-menu {
        color: #000;
    }

    .checkout-btn {
        background-color: #ff0000;
        color: #fff;
    }

    .checkout-btn:hover {
        background-color: #feccc9;
        color: #ff0000;
    }
    </style>
</head>

<body>
    <div id="alertContainer"></div>
    <br>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <a class="navbar-brand navbar-logo" href="display.php"><img src="images/logo.png" alt="Logo"
                class="logo-img">Wox to
            Box</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNav"
            aria-expanded="true" aria-controls="collapseOne">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="collapsibleNavbar">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link disabled" href="display.php" style="color:#ff1100;"><b>Product</b></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="inventory.php" style="color:#fff;">Inventory</a>
                </li>
                <?php
        if ($userRole == "Admin") {
          echo '<li class="nav-item">
          <a class="nav-link " href="revenue_graph.php" style="color:#fff;">Revenue</a>
        </li>';
        }
        ?>

                <li class="nav-item">
                    <a class="nav-link " href="account_user.php" style="color:#fff;">Account</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php" style="color:#fff;">Log Out</a>
                </li>
            </ul>
        </div>
    </nav>


    <?php
  if ($userRole == "Admin") {

    echo '<div class="container mt-4">
    <div class="row">
      <div class="col-lg-12 text-right">
        <span>
          <input type="text" name="search" size="50" placeholder="Search here..." id="search-product">
          <button class="btn btn-primary mb-3" onclick="openAddProductOverlay()"><i class="fa fa-plus"></i> Add
          Product</button>
        </span>
      </div>
    </div>
  </div>';
  }
  else{

    echo '<div class="container mt-4">
    <div class="row">
      <div class="col-lg-12 text-right">
          <input type="text" name="search" size="50" placeholder="Search here..." id="search-product">
      </div>
    </div>
  </div>';
  }
  ?>
    <!-- Product catalog -->
    <div class="container mt-4">
        <div class="row">
            <?php foreach ($products as $product) {
        // Check if the product is out of stock
        $isOutOfStock = isProductOutOfStock($product['product_id']);

        if ($product['product_state'] === 'available') {
        $imageData = $product['product_image'];
        $imageType = isset($product['image_type']) ? $product['image_type'] : '';
        $base64Image = base64_encode($imageData);
        $imageSrc = 'data:' . $imageType . ';base64,' . $base64Image;
        ?>
            <div class="col-lg-4 col-md-6 product-box">
                <div class="product <?php echo $isOutOfStock ? 'out-of-stock' : ''; ?>">
                    <div class="product-item">
                        <!-- Add the edit and delete icons here -->
                        <?php if ($userRole == "Admin") { ?>
                        <div class="product-icons">
                            <!-- Edit ingredients Icon -->
                            <a href="#" class="archive-product-icon"
                                onclick="openIngredientOverlay(<?php echo $product['product_id']; ?>)">
                                <i class="fas fa-utensils"></i>
                                <span class="action-icon-space"></span>
                            </a>
                            <!-- Edit Icon -->
                            <a href="#" class="edit-product-icon"
                                onclick="editProduct(<?php echo $product['product_id']; ?>)">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <!-- Archive Icon -->
                            <span class="action-icon-space"></span>
                            <a href="#" class="archive-product-icon"
                                onclick="showDeleteModal(<?php echo $product['product_id']; ?>)">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                        <?php } ?>

                        <a href="#">
                            <img src="<?php echo $imageSrc; ?>" alt="Product Image" class="product-image">
                        </a>
                        <div class="product-details">
                            <h4 class="product-name">
                                <?php echo $product['product_name']; ?>
                            </h4>
                            <p>Price: ₱
                                <?php echo $product['product_price']; ?>
                            </p>
                            <form action="cart.php" method="POST" onsubmit="return validateQuantity()">
                                <label for="quantity" style="font-size:18px;">Quantity:</label>
                                <div class="form-group d-flex justify-content-center">
                                    <div class="input-group custom-input-group border rounded" style="width: 150px;">
                                        <div class="input-group-prepend">
                                            <button type="button"
                                                class="btn btn-outline-secondary quantity-btn minus-btn"
                                                <?php echo $isOutOfStock ? 'disabled' : ''; ?>>-</button>
                                        </div>
                                        <input name="quantity" class="form-control quantity-input border"
                                            style="text-align:center;" inputmode="numeric" value="0" min="0" id="quan"
                                            <?php echo $isOutOfStock ? 'disabled' : ''; ?>>
                                        <div class="input-group-append">
                                            <button type="button"
                                                class="btn btn-outline-secondary quantity-btn plus-btn"
                                                <?php echo $isOutOfStock ? 'disabled' : ''; ?>>+</button>
                                        </div>
                                    </div>

                                    <!-- Add the productId as a hidden input field -->
                                    <input type="hidden" name="product_id"
                                        value="<?php echo $product['product_id']; ?>">
                                </div>
                                <button type="submit" class="btn btn-primary"
                                    <?php echo $isOutOfStock ? 'disabled' : ''; ?>>Add to
                                    Cart</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
            }
        } ?>
        </div>
    </div>


    <!-- Ingredient Details Overlay -->
    <div id="ingredientDetailsOverlay" class="overlay">
        <div class="overlay-content" style="height:50%; overflow-y:auto;">
            <button type="button" class="close" aria-label="Close" onclick="closeIngredientOverlay()">
                <span aria-hidden="true" style="font-size: 32px;">&times;</span>
            </button>
            <h3 class="overlay-title" style="text-align: center;">Ingredient Details</h3>
            <br>
            <div class="ingredient-details-content">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Ingredient Name</th>
                            <th scope="col">Quantity Required</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="ingredientDetailsTable">
                        <!-- Ingredients will be dynamically added here through AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    // Function to open the ingredient overlay
    function openIngredientOverlay(productId) {
        // Use AJAX to fetch the ingredient details for the given product ID
        $.ajax({
            url: "get_ingredients_details.php",
            type: "POST",
            data: {
                productId: productId
            },
            dataType: "json",
            success: function(response) {
                // Clear any existing data in the table
                $("#ingredientDetailsTable").empty();

                // Add the fetched ingredients to the table
                response.forEach(function(ingredient) {
                    $("#ingredientDetailsTable").append(
                        `<tr>
                <td>${ingredient.ingredient_name}</td>
                <td>
                  <input type="number" class="form-control quantity-input" disabled value="${ingredient.quantity_required}" step="0.1" min="0">
                  <input type="hidden" class="ingredient-id" value="${ingredient.ingredient_id}">
                  <input type="hidden" class="product-id" value="${productId}">
                </td>
                <td>
                  <button type="button" class="btn btn-primary edit-button">Edit</button>
                </td>
              </tr>`
                    );

                });

                // Show the ingredient details overlay
                $("#ingredientDetailsOverlay").css("display", "block");
            },
            error: function(xhr, status, error) {
                console.log("Error: " + error);
            }
        });
    }

    // Function to close the ingredient overlay
    function closeIngredientOverlay() {
        // Hide the ingredient details overlay
        $("#ingredientDetailsOverlay").css("display", "none");
    }

    // Event delegation to handle click events for "Edit" buttons
    $("#ingredientDetailsTable").on("click", ".edit-button", function() {
        const button = $(this);
        const tableRow = button.closest("tr");
        const textField = tableRow.find(".quantity-input");
        const ingredientId = tableRow.find(".ingredient-id").val();
        const productId = tableRow.find(".product-id").val();

        if (textField.prop("disabled")) {
            // Enable the text field for editing
            textField.prop("disabled", false);
            // Change the button text to "Save"
            button.text("Save");
        } else {
            const editedData = textField.val();

            console.log("Product ID: ", productId); // Check if the product ID is correct
            console.log("Ingredient ID: ", ingredientId); // Check if the ingredient ID is correct
            console.log("Edited Data: ", editedData); // Check if the edited data is correct

            // Use AJAX to update the ingredient quantity in the database
            $.ajax({
                url: "update_ingredient_quantity.php",
                type: "POST",
                data: {
                    productId: productId,
                    ingredientId: ingredientId,
                    quantityRequired: editedData
                },
                success: function(response) {
                    // Handle the success response (if needed)
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    console.log("Error: " + error);
                }
            });

            // Disable the text field after saving
            textField.prop("disabled", true);
            // Change the button text back to "Edit"
            button.text("Edit");
        }
    });
    </script>












    <?php
  function isProductOutOfStock($productId)
  {
    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "inventory_system";

    // Create a new connection
    $con = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($con->connect_error) {
      die("Connection failed: " . $con->connect_error);
    }

    // Prepare and bind a statement to check if the product is out of stock
    $stmt = $con->prepare("SELECT COUNT(*) FROM productingredient pi INNER JOIN ingredients i ON pi.ingredient_id = i.ingredient_id WHERE pi.product_id = ? AND pi.quantity_required > i.ingredient_quantity");
    $stmt->bind_param("i", $productId);
    $stmt->execute();

    // Bind the result variable
    $stmt->bind_result($outOfStockCount);

    // Fetch the data
    $stmt->fetch();

    // Close the statement and database connection
    $stmt->close();
    $con->close();

    return $outOfStockCount > 0;
  }
  ?>


    <!-- DROP UP SECTION -->
    <div class="custom-sticky-dropup">
        <div class="custom-dropup">
            <button type="button" class="custom-btn custom-btn-secondary custom-dropdown-toggle" data-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-shopping-cart"></i>
                Cart
            </button>
            <div class="custom-dropdown-menu dropdown-menu" style="width: 300px;">
                <div class="container">
                    <h4 style="text-align:center;">Cart</h4>
                    <div class="cart-items">
                        <?php
            $totalAmount = 0;

            if (isset($_SESSION['cart'])) {
              foreach ($_SESSION['cart'] as $key => $item) {
                $productID = $item['product_id'];
                $productName = $item['product_name'];
                $productPrice = $item['product_price'];
                $quantity = $item['quantity'];
                $totalPrice = $item['total_price'];

                $totalAmount += $totalPrice;
                ?>
                        <div class="cart-item">
                            <div class="cart-item-header">
                                <p class="product-id" style="font-weight:bold; font-size: 18px;">
                                    <?php echo $productID; ?>
                                </p>
                                <a href="cart.php?remove=<?php echo $key; ?>" class="remove-item"><i
                                        style="color: #5e1717;" class="fas fa-times"></i></a>
                            </div>
                            <div class="cart-item-content">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td style="font-weight:bold;">Product Name:</td>
                                            <td>
                                                <?php echo $productName; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight:bold;">Product Price:</td>
                                            <td>
                                                <?php echo $productPrice; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight:bold;">Quantity:</td>
                                            <td>
                                                <?php echo $quantity; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-weight:bold;">Total Price:</td>
                                            <td>
                                                <?php echo $totalPrice; ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php
              }
            }
            ?>

                        <form method="post" action="checkout.php">
                            <p>Total Amount:
                                <?php echo $totalAmount; ?>
                            </p>
                            <button type="submit" class="btn btn-primary checkout-btn" name="checkout">Check
                                Out</button>
                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>







    <!-- Add Product Overlay -->

    <!-- Add Product Overlay -->
    <div id="addProductOverlay" class="overlay">
        <div class="overlay-content" style="height: 80%; overflow-y: auto;">
            <!-- Close Button -->
            <button type="button" class="close" aria-label="Close" onclick="closeAddProductOverlay()">
                <span aria-hidden="true" style="font-size: 32px;">&times;</span>
            </button>
            <h3 class="overlay-title" style="text-align: center;">Add Product</h3>
            <br>
            <!-- Add Product Form -->
            <form action="add_product.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="productName">Enter Product Name</label>
                    <input type="text" class="form-control" id="productName" name="productName"
                        placeholder="Product Name" required>
                </div>
                <div class="form-group">
                    <label for="productPrice">Enter Price</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">₱</span>
                        </div>
                        <input type="number" step="1" class="form-control" id="productPrice" name="productPrice"
                            placeholder="Price" min="0" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="productImage">Upload Photo (PNG or JPG only)</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" id="productImage" name="productImage"
                            accept=".png, .jpg" required onchange="displayUploadedFile()">
                        <label class="custom-file-label" for="productImage">Choose file</label>
                    </div>
                </div>
                <!-- Ingredient Checklist -->
                <div class="form-group">
                    <label for="ingredientChecklist">Select Ingredients:</label>
                    <div class="checkbox-list">
                        <?php
            // Retrieve ingredient names from the database
            $ingredientQuery = "SELECT ingredient_id, ingredient_name FROM ingredients";
            $ingredientResult = mysqli_query($con, $ingredientQuery);

            // Generate checklist options dynamically
            while ($row = mysqli_fetch_assoc($ingredientResult)) {
              $ingredientId = $row['ingredient_id'];
              $ingredientName = $row['ingredient_name'];
              echo '<div class="form-check">';
              echo '<input class="form-check-input" type="checkbox" id="ingredient-' . $ingredientId . '" name="ingredients[]" value="' . $ingredientId . '">';
              echo '<label class="form-check-label" for="ingredient-' . $ingredientId . '">' . $ingredientName . '</label>';

              // Add an input field for the quantity consumed per product
              echo '<input type="number" class="form-control" min="0" name="ingredient_quantity[' . $ingredientId . ']" placeholder="Quantity per Cook">';

              echo '</div>';
            }
            ?>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Add Product</button>
            </form>
        </div>
    </div>








    <!-- Delete Product Confirmation Modal -->
    <div id="deleteProductModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteProductModalLabel">Confirm Deletion</h5>
                    <span class="close" onclick="closeDeleteModal()">&times;</span>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this product?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">No</button>
                    <!-- Use a form with a submit button to trigger archiving -->
                    <form action="archive_product.php" method="post" id="archiveProductForm">
                        <input type="hidden" name="product_id" id="archiveProductId">
                        <button type="submit" class="btn btn-danger" id="confirmDeleteBtn">Yes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <script>
    function showDeleteModal(productId) {
        // Show the delete confirmation modal
        const modal = document.getElementById('deleteProductModal');
        modal.style.display = 'block';

        // Set the product ID in the hidden input field
        const archiveProductId = document.getElementById('archiveProductId');
        archiveProductId.value = productId;
    }

    function closeDeleteModal() {
        // Hide the delete confirmation modal
        const modal = document.getElementById('deleteProductModal');
        modal.style.display = 'none';
    }

    // Close the modal when clicking outside the modal content
    window.onclick = function(event) {
        const modal = document.getElementById('deleteProductModal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    };
    </script>

    <script>
    function openUpdateProductOverlay() {
        console.log('openUpdateProductOverlay function called');
        document.getElementById('updateProductOverlay').style.display = 'block';
    }

    function closeUpdateProductOverlay() {
        document.getElementById('updateProductOverlay').style.display = 'none';
    }
    </script>


    <!-- Update Product Form -->
    <div id="updateProductOverlay" class="overlay">
        <div class="overlay-content">
            <!-- Close Button -->
            <button type="button" class="close" aria-label="Close"
                onclick="closeUpdateProductOverlay(); clearFileInput();">
                <span aria-hidden="true" style="font-size: 32px;">&times;</span>
            </button>
            <h3 class="overlay-title" style="text-align:center;">Update Product</h3>
            <br>
            <!-- Image Display Box -->
            <div class="image-display-box">
                <img id="productImageDisplay" src="" alt="Product Image" class="product-image-display">
            </div>

            <!-- Update Product Form -->
            <form id="updateProductForm" action="update_product.php" method="POST" enctype="multipart/form-data">


                <div class="form-group">
                    <label for="updateProductName">Enter Product Name:</label>
                    <input type="text" class="form-control" id="updateProductName" name="updateProductName"
                        placeholder="Product Name" required>
                </div>
                <div class="form-group">
                    <label for="updateProductPrice">Enter Price:</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">₱</span>
                        </div>
                        <input type="number" step="1" class="form-control" id="updateProductPrice"
                            name="updateProductPrice" placeholder="Price" required min="0">
                    </div>
                </div>

                <div class="form-group">
                    <label for="updateProductImage">Upload Image:</label>
                    <!-- The element with the ID 'updateProductImageName' is here -->
                    <input type="text" style="display:none;" class="form-control" id="updateProductImageName" readonly>
                    <div class="custom-file mt-2">

                        <input type="file" class="custom-file-input" id="updateProductImage" name="updateProductImage"
                            accept=".png, .jpg" onchange="displayUploadedUpdateFile()">
                        <label class="custom-file-label" for="updateProductImage">Choose file</label>
                    </div>
                </div>


                <!-- Add a hidden input field to hold the product ID -->
                <input type="hidden" id="updateProductId" name="productId">






                <button type="submit" class="btn btn-primary">Update Product</button>
            </form>


        </div>
    </div>




    <!-- HTML structure for the overlay -->
    <div class="overlay" id="alertOverlay">
        <div class="toast-overlay">
            <div id="alertContainer"></div>
        </div>
    </div>





    <!-- JavaScript code for success and failure alerts -->
    <script>
    function editProduct(productId) {
        console.log('editProduct function called with productId: ' + productId);
        // Call get_product.php to fetch product data based on productId
        $.ajax({
            type: 'GET',
            url: 'get_product.php',
            data: {
                productId: productId
            },
            dataType: 'json',
            success: function(response) {
                console.log('Product data response: ', response);

                if (response.error) {
                    console.log('Error fetching product data:', response.error);
                    alert('Failed to fetch product data for editing.');
                } else {
                    // Fill the data into the Update Product overlay
                    document.getElementById('updateProductId').value = productId;
                    document.getElementById('updateProductName').value = response.productName;
                    document.getElementById('updateProductPrice').value = response.productPrice;
                    document.getElementById('updateProductImageName').value = response
                        .imageName; // Populating the image name input field
                    document.getElementById('productImageDisplay').src = response
                        .imageSrc; // Update the image display box

                    // Call the function to open the Update Product overlay with the productId
                    openUpdateProductOverlay();
                }
            },
            error: function(xhr, status, error) {
                console.log('Error:', error);
                alert('Failed to fetch product data for editing.');
            }
        });
    }
    </script>




    <script>
    function displayUploadedUpdateFile() {
        var input = document.getElementById('updateProductImage');
        var fileName = input.files[0].name;
        var label = document.querySelector('.custom-file-label[for="updateProductImage"]');
        label.innerText = fileName;
    }
    </script>

    <script>
    function clearCheckboxes() {
        var checkboxes = document.querySelectorAll('input[type="checkbox"][name="ingredients[]"]');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = false;
        });
    }

    function clearFileInput() {
        // Get the file input element
        var fileInput = document.getElementById('updateProductImage');

        // Clear the value of the file input
        fileInput.value = '';

        // Reset the label text to its default
        var label = document.querySelector('.custom-file-label[for="updateProductImage"]');
        label.innerText = 'Current Image Uploaded';
    }
    </script>






    <!-- This is for the add and minus part of qquantity-->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var minusBtns = document.getElementsByClassName("minus-btn");
        var plusBtns = document.getElementsByClassName("plus-btn");
        var quantityInputs = document.getElementsByClassName("quantity-input");

        // Add event listeners to minus buttons
        for (var i = 0; i < minusBtns.length; i++) {
            minusBtns[i].addEventListener("click", function() {
                var input = this.parentNode.nextElementSibling;
                if (input.value > 0) {
                    input.value--;
                }
            });
        }

        // Add event listeners to plus buttons
        for (var i = 0; i < plusBtns.length; i++) {
            plusBtns[i].addEventListener("click", function() {
                var input = this.parentNode.previousElementSibling;
                input.value++;
            });
        }
    });
    </script>
    <!-- checks if the quantity is not zero-->



    <!-- JavaScript code for success and failure alerts -->
    <script>
    // Check if the URL contains success parameter
    <?php if (isset($_GET['success'])) { ?>
    $(document).ready(function() {
        <?php if ($_GET['success'] === 'true') { ?>
        // Create the success alert
        var successAlert = $('<div class="alert alert-success alert-dismissible fade show" role="alert">')
            .text("<?php echo urldecode($_GET['message']); ?>");

        // Show the success alert in the alertContainer element
        $('#alertContainer').html(successAlert);
        $('#alertContainer').addClass('show');
        <?php } elseif ($_GET['success'] === 'false') { ?>
        // Create the error alert
        var errorAlert = $('<div class="alert alert-danger alert-dismissible fade show" role="alert">')
            .text("<?php echo urldecode($_GET['message']); ?>");

        // Show the error alert in the alertContainer element
        $('#alertContainer').html(errorAlert);
        $('#alertContainer').addClass('show');
        <?php } ?>

        // Countdown to hide the alert
        setTimeout(function() {
            $('#alertContainer').removeClass('show');
        }, 2000);
    });
    <?php } ?>
    </script>



    <script>
    function openAddProductOverlay() {
        document.getElementById("addProductOverlay").style.display = "block";
    }

    function closeAddProductOverlay() {
        document.getElementById("addProductOverlay").style.display = "none";
    }
    </script>
    <script>
    function displayUploadedFile() {
        var input = document.getElementById('productImage');
        var fileName = input.files[0].name;
        var label = document.querySelector('.custom-file-label');
        label.innerText = fileName;
    }
    </script>



    <script>
    // JavaScript code for drop-up functionality
    document.addEventListener('DOMContentLoaded', function() {
        var dropupButton = document.querySelector('.custom-dropup .dropdown-toggle');
        var dropupMenu = document.querySelector('.custom-dropup .custom-dropdown-menu');

        dropupButton.addEventListener('click', function() {
            dropupMenu.classList.toggle('show');
        });

        document.addEventListener('click', function(event) {
            var target = event.target;
            if (!dropupButton.contains(target) && !dropupMenu.contains(target)) {
                dropupMenu.classList.remove('show');
            }
        });
    });
    </script>


    <script>
    $(document).ready(function() {
        $('.navbar-toggler').on('click', function() {
            $('.navbar-collapse').toggleClass('show');
        });
    });
    </script>

    <footer class="footer">
        <div class="container">
            <p>&copy;
                <?php echo date('Y'); ?> Wox to Box. All rights reserved.
            </p>
        </div>
    </footer>
</body>

</html>

<?php
mysqli_close($con);
?>