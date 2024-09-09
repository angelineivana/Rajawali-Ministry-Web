<?php
include '../../db_config.php';
include('../session_check.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $description = $_POST["description"];
    $country = $_POST["country"];
    $city = $_POST["city"];
    $photo_url = $_POST["photo_url"];
    $hotel_name = $_POST["hotel_name"];

    $sql = "INSERT INTO destinations (name, description, country, city, photo_url, hotel_name) 
            VALUES ('$name', '$description', '$country', '$city', '$photo_url', '$hotel_name')";

    if ($conn->query($sql) === TRUE) {
        echo "New destination added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<body>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles.css">
</head>
<h2>Add Destination</h2>
<form method="post" action="">
  Name: <input type="text" name="name"><br>
  Description: <textarea name="description"></textarea><br>
  Country: <input type="text" name="country"><br>
  City: <input type="text" name="city"><br>
  Photo Link: <input type="text" name="photo_url"><br>
  Hotel Name: <input type="text" name="hotel_name"><br>

  <input type="submit" value="Submit">
</form>

<a href="../admin-index.php">
Back to Admin Dashboard</a>

</body>
</html>
