<?php
// Check if the form is submitted
if (isset($_POST['uploadBtn'])) {
  // Database connection details
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "example";

  // Create a new connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Get the uploaded image file
  $image = $_FILES['imageUpload']['tmp_name'];

  // Read the contents of the image file
  $imageData = file_get_contents($image);

  // Escape special characters in the image data
  $escapedImageData = $conn->real_escape_string($imageData);

  // Insert the image data into the database
  $sql = "INSERT INTO photo (image) VALUES ('$escapedImageData')";

  if ($conn->query($sql) === TRUE) {
    echo "Image uploaded successfully.";
  } else {
    echo "Error uploading image: " . $conn->error;
  }

  // Close the database connection
  $conn->close();
}
?>
