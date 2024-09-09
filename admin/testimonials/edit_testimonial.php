<?php
include '../../db_config.php';
include('../session_check.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $customer_name = $_POST["customer_name"];
    $review = $_POST["review"];
    $rating = $_POST["rating"];
    $date = $_POST["date"];
    $video_url = $_POST["video_url"];

    $sql = "UPDATE testimonials SET 
                customer_name='$customer_name', 
                review='$review', 
                rating='$rating', 
                date='$date', 
                video_url='$video_url' 
            WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Testimonial updated successfully";
        header("Location: list_testimonials.php");
        exit();
    } else {
        echo "Error updating testimonial: " . $conn->error;
    }
} elseif (isset($_GET["id"])) {
    $id = $_GET["id"];
    $result = $conn->query("SELECT * FROM testimonials WHERE id=$id");

    if ($result->num_rows > 0) {
        $testimonial = $result->fetch_assoc();
    } else {
        echo "Testimonial not found";
        exit;
    }
} else {
    echo "Testimonial ID not provided";
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

<h2>Edit Testimonial</h2>
<form method="post" action="">
  <input type="hidden" name="id" value="<?php echo $testimonial['id']; ?>">
  Customer Name: <input type="text" name="customer_name" value="<?php echo $testimonial['customer_name']; ?>"><br>
  Review: <textarea name="review"><?php echo $testimonial['review']; ?></textarea><br>
  Rating: <input type="number" name="rating" value="<?php echo $testimonial['rating']; ?>" min="1" max="5"><br>
  Date: <input type="date" name="date" value="<?php echo $testimonial['date']; ?>"><br>
  Video URL: <input type="text" name="video_url" value="<?php echo $testimonial['video_url']; ?>"><br>
  <input type="submit" value="Submit">
</form>

<a href="../admin-index.php">
Back to Admin Dashboard</a>

</body>
</html>
