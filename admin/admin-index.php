<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rajawali Ministry Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .action-link {
            display: block;
            margin-bottom: 5px;
            text-decoration: none;
            color: #333;
            padding: 8px;
            border: 1px solid #ccc;
            background-color: #f0f0f0;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .action-link:hover {
            background-color: #e0e0e0;
        }
    </style>
</head>
<body>
    <h1>Rajawali Ministry Admin Panel</h1>

    <div class="section">
        <div class="section-title">Tours</div>
        <a href="tours/add_tour.php" class="action-link">Add Tour</a>
        <a href="tours/choose_edit.php" class="action-link">Edit Tour</a>
        <a href="tours/choose_delete.php" class="action-link">Delete Tour</a>
        <a href="tours/list_tours.php" class="action-link">List All Tours</a>
    </div>

    <div class="section">
        <div class="section-title">Destinations</div>
        <a href="destinations/add_destination.php" class="action-link">Add Destination</a>
        <a href="destinations/list_destinations.php" class="action-link">List All Destinations</a>
    </div>

    <div class="section">
        <div class="section-title">Testimonials</div>
        <a href="testimonials/add_testimonial.php" class="action-link">Add Testimonial</a>
        <a href="testimonials/list_testimonials.php" class="action-link">List All Testimonials</a>
    </div>

    <div class="section">
        <div class="section-title">Contact Us</div>
        <a href="contact_us/list_messages.php" class="action-link">List All Messages</a>
    </div>

    <div class="section">
        <div class="section-title">Photos</div>
        <a href="photos/add_photo.php" class="action-link">Add Photo</a>
        <a href="photos/list_photos.php" class="action-link">List All Photos</a>
    </div>
    
    <div class="section">
        <div class="section-title">FAQ</div>
        <a href="faq/add_faq.php" class="action-link">Add FAQ</a>
        <a href="faq/list_faq.php" class="action-link">List All FAQ</a>
    </div>

</body>
</html>
