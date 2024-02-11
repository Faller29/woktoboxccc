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


// Fetch products from the database
$sql = "SELECT * FROM product";
$result = mysqli_query($con, $sql);
$archivedProducts = mysqli_fetch_all($result, MYSQLI_ASSOC);

?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archived Product</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory System</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
        integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <style>
        tr {
            height: 70px;
        }

        td,
        th {
            text-align: center;
            vertical-align: middle;
        }

        .logo-img {
            height: 50px;
            width: auto;
            /* This will scale the logo proportionally based on the height */
        }

        .action-icon-space {
            margin-left: 15px;
        }

        .fa-trash,
        .fa-pencil-alt,
        .fa-plus {
            font-size: 20px;
        }

        .action-icon-space {
            margin-left: 20%;
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
    </style>

</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <a class="navbar-brand navbar-logo" href="display.php"><img src="images/logo.png" alt="Logo"
                class="logo-img">Wox to
            Box</a>

        <div class="collapse navbar-collapse justify-content-end" id="collapsibleNavbar">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link " href="display.php">Product</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="inventory.php">Inventory</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="revenue_graph.php">Revenue</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " href="account_user.php">Account</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Log Out</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Archived Product catalog -->
    <div class="container mt-4">
        <div class="row">
            <?php foreach ($archivedProducts as $archivedProduct) {
                if ($archivedProduct['product_state'] === 'unavailable') {

                    $imageData = $archivedProduct['product_image'];
                    $imageType = isset($archivedProduct['image_type']) ? $archivedProduct['image_type'] : '';
                    $base64Image = base64_encode($imageData);
                    $imageSrc = 'data:' . $imageType . ';base64,' . $base64Image;


                    ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="product ">
                            <div class="product-item">
                                <!-- edit and restore icons here -->
                                <div class="product-icons">


                                    <!-- Restore Icon -->
                                    <a href="#" class="restore-product-icon"
                                        onclick="showRestoreModal(<?php echo $archivedProduct['product_id']; ?>)">
                                        <i class="fas fa-undo-alt"></i>

                                    </a><span class="action-icon-space"></span>
                                    <a href="#" class="archive-product-icon"
                                        onclick="showDeleteModal(<?php echo $archivedProduct['product_id']; ?>)">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                                <!-- Archive Icon -->


                                <a href="#"></a>
                                <img src="<?php echo $imageSrc; ?>" alt="Product Image" class="product-image">
                                </a>
                                <div class="product-details">
                                    <h4>
                                        <?php echo $archivedProduct['product_name']; ?>
                                    </h4>
                                    <p>Price: ₱
                                        <?php echo $archivedProduct['product_price']; ?>
                                    </p>
                                    <!-- Display the quantity, but don't allow editing -->
                                    <div class="form-group d-flex justify-content-center">
                                        <div class="input-group custom-input-group border rounded" style="width: 150px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                }
            } ?>
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
                    <form action="perma_delete_product.php" method="post" id="archiveProductForm">
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
        window.onclick = function (event) {
            const modal = document.getElementById('deleteProductModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        };

    </script>
    <!-- Restore Product Confirmation Modal -->
    <div id="restoreProductModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="restoreProductModalLabel">Confirm Restoration</h5>
                    <span class="close" onclick="closeRestoreModal()">&times;</span>
                </div>
                <div class="modal-body">
                    Are you sure you want to restore this product?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeRestoreModal()">No</button>
                    <!-- Use a form with a submit button to trigger restoration -->
                    <form action="restore_product.php" method="post" id="restoreProductForm">
                        <input type="hidden" name="restoreProduct" id="restoreProductId">
                        <button type="submit" class="btn btn-success" id="confirmRestoreBtn">Yes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showRestoreModal(productId) {
            // Show the restore confirmation modal
            const modal = document.getElementById('restoreProductModal');
            modal.style.display = 'block';

            // Set the product ID in the hidden input field
            const restoreProductId = document.getElementById('restoreProductId');
            restoreProductId.value = productId;
        }

        function closeRestoreModal() {
            // Hide the restore confirmation modal
            const modal = document.getElementById('restoreProductModal');
            modal.style.display = 'none';
        }

        // Close the modal when clicking outside the modal content
        window.onclick = function (event) {
            const modal = document.getElementById('restoreProductModal');
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        };
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