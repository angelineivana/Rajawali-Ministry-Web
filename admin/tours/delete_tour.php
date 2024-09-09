<?php
include '../../db_config.php';
include('../session_check.php');

// Check if ID is provided via GET
if (!isset($_GET["id"]) || empty($_GET["id"])) {
    die("Tour ID not provided");
}

// Sanitize the ID to prevent SQL injection
$id = mysqli_real_escape_string($conn, $_GET["id"]);

// Construct the SQL DELETE query
$sql = "DELETE FROM tours WHERE id=$id";

// Execute the DELETE query
if ($conn->query($sql) === TRUE) {
    echo "Tour deleted successfully";
} else {
    echo "Error deleting tour: " . $conn->error;
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles.css">
</head>
<body>

<a href="../admin-index.php">
Back to Admin Dashboard</a>

</body>
</html>
