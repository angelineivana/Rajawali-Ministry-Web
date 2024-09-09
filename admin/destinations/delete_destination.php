<?php
include '../../db_config.php';
include('../session_check.php');

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    die("Destination ID not provided");
}

$id = mysqli_real_escape_string($conn, $_GET["id"]);

$sql = "DELETE FROM destinations WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    echo "Destination deleted successfully";
} else {
    echo "Error deleting destination: " . $conn->error;
}

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
