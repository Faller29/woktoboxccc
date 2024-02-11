<?php
session_start();
$userRole = $_SESSION['userRole'];

$servername = "localhost";
$username = "root";
$password = "";
$database = "inventory_system";
$con = mysqli_connect($servername, $username, $password, $database);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}




?>

<!DOCTYPE html>
<html lang="en">

<head>
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


    <style>
        tr {
            height: 70px;
        }

        td,
        th {
            text-align: center;
            vertical-align: middle;
        }

        th {
            width: 150px;
            text-align: center;
        }

        td {
            text-align: center;
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
            margin-left: 40%;
        }

        .table-shadow {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

        <?php
            if ($userRole == "Admin") {
                ?>
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
        
        <?php } ?>

    </style>

</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <a class="navbar-brand navbar-logo" href="display.php"><img src="images/logo.png" alt="Logo"
                class="logo-img">Wox to
            Box</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar"
            aria-expanded="true" aria-controls="collapseOne">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="collapsibleNavbar">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link " href="display.php">Product</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="inventory.php">Inventory</a>
                </li>
                <?php if ($userRole == "Admin") { ?>
                    <li class="nav-item">
                        <a class="nav-link " href="revenue_graph.php">Revenue</a>
                    </li>
                <?php } ?>
                <li class="nav-item">
                    <a class="nav-link " href="account_user.php">Account</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Log Out</a>
                </li>
            </ul>
        </div>
    </nav>





    <div id="alertContainer"></div>
    <br>

    <!-- Add Product Button and Category Button -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-12 text-right">
                <!-- Sorting Dropdown -->
                <input type="text" name="search" id="inventory-search" placeholder="Search here...">
                <select class="form-control d-inline-block w-auto" id="filter-search" onchange="changeFilter()">
                    <option value="percentage">Name</option>
                    <option value="category">Category</option>
                    <option value="quantity">Quantity</option>
                </select>
                <label for="sortingDropdown" style="margin-bottom:20px;">Sorted by:</label>
                <select class="form-control d-inline-block w-auto" id="sortingDropdown">
                    <option value="percentage">Percent</option>
                    <option value="category">Category</option>
                    <option value="name">Name</option>
                    <option value="quantity">Quantity</option>
                </select>
                <?php if ($userRole == "Admin") { ?>
                    <div class="d-flex justify-content-between align-items-center">

                        <!-- Category Button -->
                        <form method="GET" action="category.php">
                            <button class="btn btn-primary mb-3">
                                Category
                            </button>
                        </form>
                        <!-- Add Button -->
                        <button class="btn btn-primary mb-3" onclick="addProductOverlay.style.display='block'">
                            <i class="fa fa-plus"></i> Add Ingredient
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>



    <!-- Display the ingredient -->
    <div class="container mt-4">
        <table class="table table-shadow">
            <thead class="thead-light">
                <tr>
                    <th scope="col">ID no.</th>
                    <th scope="col">Ingredient Name</th>
                    <th scope="col">Category</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Minimum Level</th>
                    <?php if ($userRole == "Admin") {
                        echo '<th scope="col">Action</th>';
                    } ?>
                    <!-- Add additional table headers if needed -->
                </tr>
            </thead>
            <tbody>
                <?php
                // Retrieve data from the ingredients table and join with ingredient_category table
                $sortBy = "percentage"; // Default sorting by category
                if (isset($_GET['sort'])) {
                    $sortBy = $_GET['sort'];
                }

                $sql = "SELECT ingredients.ingredient_id, ingredients.ingredient_name, ingredient_category.category,
        ingredients.ingredient_quantity, ingredients.ingredient_level
        FROM ingredients
        LEFT JOIN ingredient_category ON ingredients.category_id = ingredient_category.category_id
        ORDER BY ";

                switch ($sortBy) {
                    case 'percentage':
                        $sql .= "ingredients.ingredient_quantity / ingredients.ingredient_level ASC";
                        break;
                    case 'name':
                        $sql .= "ingredients.ingredient_name ASC";
                        break;
                    case 'quantity':
                        $sql .= "ingredients.ingredient_quantity ASC";
                        break;
                    case 'category':
                    default:
                        $sql .= "ingredient_category.category ASC";
                        break;
                }

                $result = mysqli_query($con, $sql);
                $result = mysqli_query($con, $sql);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $ingredientId = $row['ingredient_id'];
                        $ingredientName = $row['ingredient_name'];
                        $ingredientCategory = $row['category'];
                        $ingredientQuantity = $row['ingredient_quantity'];
                        $ingredientLevel = $row['ingredient_level'];

                        // Calculate the percentage of quantity over the minimum level
                        $percentage = ($ingredientQuantity / $ingredientLevel) * 100;
                        $rowColorClass = '';

                        // Set the row color based on the percentage value
                        if ($percentage <= 80) {
                            // If the quantity is less than or equal to 20% of the minimum level, set color red
                            $rowColorClass = 'bg-danger text-white';
                        } elseif ($percentage <= 100) {
                            // If the quantity is greater than 20% but less than or equal to 100% of the minimum level, set color yellow
                            $rowColorClass = 'bg-warning';
                        }

                        echo '<tr class="' . $rowColorClass . ' item-row">
                    <th scope="row">' . $ingredientId . '</th>
                    <td>' . $ingredientName . '</td>
                    <td>' . $ingredientCategory . '</td>
                    <td>' . $ingredientQuantity . '</td>
                    <td>' . $ingredientLevel . '</td>';
                        if ($userRole == "Admin") {
                            echo '
                    
                         <td>
                            <a href="#" onclick="openEditOverlay(' . $ingredientId . ')">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <span class="action-icon-space"></span>
                            <a href="#" onclick="showDeleteConfirmationModal(' . $ingredientId . ')">
                                <i class="fa fa-trash" style="color:red;"></i>
                            </a>
                        </td>';

                            echo '</tr>';
                        }

                    }
                }
                ?>
            </tbody>

        </table>
    </div>





    <!-- Delete confirmation -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog"
        aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this ingredient?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger" id="deleteButton">Yes</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Delete confirmation -->
    <script>
        function showDeleteConfirmationModal(ingredientId) {
            document.getElementById('deleteButton').setAttribute('data-ingredient-id', ingredientId);

            $('#confirmDeleteModal').modal('show');
        }

        document.getElementById('deleteButton').addEventListener('click', function () {
            var ingredientId = this.getAttribute('data-ingredient-id');

            // Use AJAX to send the request to delete_ingredient.php
            $.ajax({
                url: 'delete_ingredient.php',
                type: 'POST',
                data: {
                    removeIngredient: ingredientId
                },
                success: function (response) {
                    var parsedResponse = JSON.parse(response);

                    if (parsedResponse.success) {
                        // Reload the page after successful deletion
                        window.location.reload();
                    } else {
                        alert('Failed to delete ingredient.');
                    }

                    // Hide the modal after the AJAX request
                    $('#confirmDeleteModal').modal('hide');
                },
                error: function () {
                    alert('Error occurred while processing the request.');
                }
            });
        });
    </script>


    <!-- Category Overlay -->
    <div id="categoryOverlay" class="overlay" style="display: none;">
        <div class="overlay-content">
            <!-- Close Button -->
            <button type="button" class="close" aria-label="Close" onclick="closeCategoryOverlay()">
                <span aria-hidden="true" style="font-size: 32px;">&times;</span>
            </button>
            <h3 class="overlay-title" style="text-align:center;">Category</h3>
            <br>
            <!-- Add Category Form -->
            <form action="add_category.php" method="POST">
                <div class="form-group">
                    <label for="categoryName">Add Category</label>
                    <input type="text" class="form-control" id="categoryName" name="categoryName"
                        placeholder="Category Name" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Category</button>
            </form>
            <br>

            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Operation</th>

                    </tr>
                </thead>
                <tbody>

                    <?php

                    //this will display the account in our database using sql in table format
                    $sql = "SELECT * FROM `ingredient_category`";
                    $result = mysqli_query($con, $sql);

                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $id = $row['category_id'];
                            $categName = $row['category'];
                            echo '<tr>
                                <th scope="row">' . $id . '</th>
                                <td>' . $categName . '</td>
                                <td>
                                    <button class="btn btn-danger" onclick="openConfirmationModal(' . $id . ')">Delete</button>
                                </td>
                            </tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>


    <!-- Confirmation delete modal -->
    <div id="confirmDeleteModal" class="custom-modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation</h5>
                <button type="button" class="close" onclick="closeConfirmationModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this record?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeConfirmationModal()">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn"
                    onclick="confirmDelete()">Delete</button>
            </div>
        </div>
    </div>
    <script>
        // Function to show the category overlay
        function showCategoryOverlay() {
            document.getElementById("categoryOverlay").style.display = "block";
        }

        // Function to close the category overlay
        function closeCategoryOverlay() {
            document.getElementById("categoryOverlay").style.display = "none";
        }

        // Function to open the confirmation modal
        function openConfirmationModal(categoryId) {
            deleteId = categoryId;
            document.getElementById("confirmDeleteModal1").style.display = "block";
        }

        // Function to close the confirmation modal
        function closeConfirmationModal() {
            document.getElementById("confirmDeleteModal1").style.display = "none";
        }

        // Function to handle category deletion
        function confirmDelete() {
            // Your AJAX code to delete the category here
            // Once the deletion is successful, close the confirmation modal
            closeConfirmationModal();
        }
    </script>



    <!-- Edit Product Overlay -->
    <div id="editProductOverlay" class="overlay">
        <div class="overlay-content">
            <button type="button" class="close" aria-label="Close" onclick="closeEditOverlay()">
                <span aria-hidden="true" style="font-size: 32px;">&times;</span>
            </button>
            <h3 class="overlay-title" style="text-align:center;">Edit Ingredient</h3>
            <br>
            <!-- Update the form action to point to update_ingredient.php -->
            <form id="editForm" method="POST" action="update_ingredient.php">
                <!-- Add a hidden input field to store the ingredient ID -->
                <input type="hidden" id="editIngredientId" name="editIngredientId" value="">
                <div class="form-group">
                    <label for="editIngredientName">Ingredient Name</label>
                    <input type="text" class="form-control" id="editIngredientName" name="editIngredientName" required>
                </div>
                <div class="form-group">
                    <label for="editIngredientCategory">Category</label>
                    <select class="form-control" id="editIngredientCategory" name="editIngredientCategory" required>
                        <!-- Fetch and display all categories from the database -->
                        <?php
                        $sqlCategories = "SELECT * FROM `ingredient_category`";
                        $resultCategories = mysqli_query($con, $sqlCategories);
                        if ($resultCategories && mysqli_num_rows($resultCategories) > 0) {
                            while ($rowCategory = mysqli_fetch_assoc($resultCategories)) {
                                $categoryId = $rowCategory['category_id'];
                                $categoryName = $rowCategory['category'];
                                echo '<option value="' . $categoryId . '">' . $categoryName . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="editIngredientQuantity">Quantity</label>
                    <div class="input-group">
                        <input type="number" min="0" step="1" class="form-control" id="editIngredientQuantity"
                            name="editIngredientQuantity" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="editIngredientLevel">Minimum Level</label>
                    <div class="input-group">
                        <input type="number" min="0" step="1" class="form-control" id="editIngredientLevel"
                            name="editIngredientLevel" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>
    </div>


    <script>
        // Function to open the edit overlay and fetch ingredient details from the database
        function openEditOverlay(ingredientId) {
            // Make an AJAX request to fetch the ingredient details
            $.ajax({
                url: "get_ingredient_details.php",
                type: "POST",
                data: { ingredientId: ingredientId },
                dataType: "json",
                success: function (response) {
                    // Fill the form with the fetched ingredient details
                    $("#editIngredientId").val(response.ingredient_id);
                    $("#editIngredientName").val(response.ingredient_name);
                    $("#editIngredientQuantity").val(response.ingredient_quantity);
                    $("#editIngredientLevel").val(response.ingredient_level);

                    // Fetch and populate the category dropdown
                    fetchCategories(response.category_id);

                    // Show the edit overlay
                    $("#editProductOverlay").css("display", "block");
                },
                error: function (xhr, status, error) {
                    console.log("Error: " + error);
                }
            });
        }

        // Function to fetch and populate the category dropdown
        function fetchCategories(selectedCategoryId) {
            $.ajax({
                url: "get_categories.php",
                type: "POST",
                dataType: "json",
                success: function (response) {
                    var options = "";
                    for (var i = 0; i < response.length; i++) {
                        var categoryId = response[i].category_id;
                        var categoryName = response[i].category;

                        // Check if this option is the selected category
                        var selected = (categoryId == selectedCategoryId) ? "selected" : "";

                        options += '<option value="' + categoryId + '" ' + selected + '>' + categoryName + '</option>';
                    }

                    // Populate the dropdown options
                    $("#editIngredientCategory").html(options);
                },
                error: function (xhr, status, error) {
                    console.log("Error: " + error);
                }
            });
        }

        // Function to close the edit overlay and reset form fields
        function closeEditOverlay() {
            // Reset the form fields
            $("#editForm")[0].reset();
            $("#editIngredientId").val("");
            // Hide the edit overlay
            $("#editProductOverlay").css("display", "none");
        }
    </script>





    <!--Add Ingredient Overlay-->
    <div id="addProductOverlay" class="overlay">
        <div class="overlay-content">
            <!-- Close Button -->
            <button type="button" class="close" aria-label="Close" onclick="addProductOverlay.style.display='none'">
                <span aria-hidden="true" style="font-size: 32px;">&times;</span>
            </button>
            <h3 class="overlay-title" style="text-align:center;">Add Ingredient</h3>
            <br>
            <!-- Add Product Form -->
            <form action="add_ingredient.php" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="ingredientName">Enter Ingredient Name</label>
                    <input type="text" class="form-control" id="ingredientName" name="ingredientName"
                        placeholder="Ingredient Name" required>
                </div>

                <div class="form-group">
                    <label for="ingredientCategory">Select Category</label>
                    <select class="form-control" id="ingredientCategory" name="ingredientCategory" required>
                        <option value="" selected disabled>Select a category</option>
                        <!-- Fetch and display all categories from the database -->
                        <?php
                        $sqlCategories = "SELECT * FROM `ingredient_category`";
                        $resultCategories = mysqli_query($con, $sqlCategories);
                        if ($resultCategories && mysqli_num_rows($resultCategories) > 0) {
                            while ($rowCategory = mysqli_fetch_assoc($resultCategories)) {
                                $categoryId = $rowCategory['category_id'];
                                $categoryName = $rowCategory['category'];
                                echo '<option value="' . $categoryId . '">' . $categoryName . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="ingredient">Enter Quantity</label>
                    <div class="input-group">
                        <input type="number" min="0" step="1" class="form-control" id="ingredient"
                            name="ingredientQuantity" placeholder="Quantity" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="ingredient">Enter Ingredient Minimum Level</label>
                    <div class="input-group">
                        <input type="number" step="1" min="0" class="form-control" id="ingredient"
                            name="ingredientLevel" placeholder="Minimum Level" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Add Ingredient</button>
            </form>
        </div>
    </div>




    <!-- Remove Product Overlay -->
    <!-- open the file deleteingredient to see this -->







    <script>
        // Check if the URL contains success parameter
        <?php if (isset($_GET['success'])) { ?>
            $(document).ready(function () {
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
                setTimeout(function () {
                    $('#alertContainer').removeClass('show');
                }, 3000);
                                                                                                    });
    <?php } ?>
    </script>

    <script>
        let inventory_search = document.getElementById("inventory-search");
        let item_row = document.getElementsByClassName("item-row");
        let filter_search = document.getElementById("filter-search");
        
        let option = "Name";
        let filter = 1;

        inventory_search.addEventListener("input", e =>{
            const value = e.target.value
            switch(option){
                    case "Name":
                        filter = 1
                        break;
                    case "Category":
                        filter = 2
                        break;
                    case "Quantity":
                        filter = 3
                        break;
                }
            for(let i = 0; i < item_row.length; i++){
                if(item_row[i].children[filter].innerHTML.trim().toLowerCase().includes(value)){
                    item_row[i].style.display = "table-row"
                }
                else{
                    item_row[i].style.display = "none"
                }
            }
        })
        function changeFilter(){
            option = filter_search.options[filter_search.selectedIndex].text
            console.log(option)
        }
    </script>

    <footer class="footer">
        <div class="container1">
            <p class="foot"></p>&copy;
            <?php echo date('Y'); ?> Wox to Box. All rights reserved.
            </p>
        </div>
    </footer>

</body>

</html>