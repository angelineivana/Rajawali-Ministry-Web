<?php
include '../../db_config.php';
include('../session_check.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $description = $_POST["description"];
    $country = $_POST["country"];
    $city = $_POST["city"];
    $photo_url = $_POST["photo_url"];
    $hotel_name = $_POST["hotel_name"];

    $sql = "UPDATE destinations SET 
                name='$name', 
                description='$description', 
                country='$country', 
                city='$city', 
                photo_url='$photo_url', 
                hotel_name='$hotel_name' 
            WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Updated successfully";
        header("Location: list_destinations.php");
        exit();
    } else {
        echo "Error updating destination: " . $conn->error;
    }
} elseif (isset($_GET["id"])) {
    $id = $_GET["id"];
    $result = $conn->query("SELECT * FROM destinations WHERE id=$id");

    if ($result->num_rows > 0) {
        $destination = $result->fetch_assoc();
    } else {
        echo "Destination not found";
        exit;
    }
} else {
    echo "Destination ID not provided";
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

<h2>Edit Destination</h2>
<form method="post" action="">
  <input type="hidden" name="id" value="<?php echo $destination['id']; ?>">
  Name: <input type="text" name="name" value="<?php echo $destination['name']; ?>"><br>
  Description: <textarea name="description"><?php echo $destination['description']; ?></textarea><br>
  Country: <input type="text" name="country" value="<?php echo $destination['country']; ?>"><br>
  City: <input type="text" name="city" value="<?php echo $destination['city']; ?>"><br>
  Photo URL: <input type="text" name="photo_url" value="<?php echo $destination['photo_url']; ?>"><br>
  Hotel Name: <input type="text" name="hotel_name" value="<?php echo $destination['hotel_name']; ?>"><br>
  <input type="submit" value="Submit">
</form>

<a href="../admin-index.php">
Back to Admin Dashboard</a>

</body>
</html>
