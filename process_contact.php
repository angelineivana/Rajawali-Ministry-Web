<?php
// Include the database configuration file
include('db_config.php');


// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data and sanitize it
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // Insert data into the database
    $sql = "INSERT INTO contactus (name, phone, message) VALUES ('$name', '$phone', '$message')";

    if ($conn->query($sql) === TRUE) {
        // Redirect back to index.php with a success message
        header("Location: index.php?status=success");
    } else {
        // Redirect back to index.php with an error message
        header("Location: index.php?status=error");
    }
    // Close the database connection
    $conn->close();
    exit; // Ensure no further code is executed

}
?>
