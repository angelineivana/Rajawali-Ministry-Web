<?php
include '../../db_config.php';
include('../session_check.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $url = $_POST["url"];

    $sql = "INSERT INTO photos (url) 
            VALUES ('$url')";

    if ($conn->query($sql) === TRUE) {
        echo "New photo added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles.css">
</head>
<body>

<h2>Add Photo</h2>
<form method="post" action="">
  URL: <input type="text" name="url"><br>
  <input type="submit" value="Submit">
</form>

<a href="../admin-index.php">
Back to Admin Dashboard</a>

</body>
</html>
