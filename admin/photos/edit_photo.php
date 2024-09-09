<?php
include '../../db_config.php';
include('../session_check.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $url = $_POST["url"];

    $sql = "UPDATE photos SET url='$url' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Photo updated successfully";
    } else {
        echo "Error updating photo: " . $conn->error;
    }
} elseif (isset($_GET["id"])) {
    $id = $_GET["id"];
    $result = $conn->query("SELECT * FROM photos WHERE id=$id");

    if ($result->num_rows > 0) {
        $photo = $result->fetch_assoc();
    } else {
        echo "Photo not found";
        exit;
    }
} else {
    echo "Photo ID not provided";
    exit;
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

<h2>Edit Photo</h2>
<form method="post" action="">
  <input type="hidden" name="id" value="<?php echo $photo['id']; ?>">
  URL: <input type="text" name="url" value="<?php echo $photo['url']; ?>"><br>
  <input type="submit" value="Submit">
</form>

<a href="../admin-index.php">
Back to Admin Dashboard</a>

</body>
</html>
