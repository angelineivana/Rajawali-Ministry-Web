<?php
include '../../db_config.php';
include('../session_check.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_name = $_POST["customer_name"];
    $review = $_POST["review"];
    $rating = $_POST["rating"];
    $date = $_POST["date"];
    $video_url = $_POST["video_url"];

    $sql = "INSERT INTO testimonials (customer_name, review, rating, date, video_url) 
            VALUES ('$customer_name', '$review', '$rating', '$date', '$video_url')";

    if ($conn->query($sql) === TRUE) {
        echo "New testimonial added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Testimonial</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>

<div class="container">
    <h2>Add Testimonial</h2>
    
    <form method="post" action="">
        <label for="customer_name">Customer Name:</label>
        <input type="text" id="customer_name" name="customer_name"><br><br>
        
        <label for="review">Review:</label><br>
        <textarea id="review" name="review"></textarea><br><br>
        
        <label for="rating">Rating:</label>
        <input type="number" id="rating" name="rating" min="1" max="5"><br><br>
        
        <label for="date">Date of Tour Departure:</label>
        <input type="date" id="date" name="date"><br><br>
        
        <label for="video_url">Video Link (Youtube):</label>
        <input type="text" id="video_url" name="video_url"><br><br>
        
        <input type="submit" value="Submit">
    </form>
    
    <a href="../admin-index.php">
Back to Admin Dashboard</a>
</div>

</body>
</html>

