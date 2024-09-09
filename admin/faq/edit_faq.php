<?php
include '../../db_config.php';
include('../session_check.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $question = $_POST["question"];
    $answer = $_POST["answer"];

    $sql = "UPDATE faq SET question='$question', answer='$answer' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "FAQ updated successfully";
    } else {
        echo "Error updating FAQ: " . $conn->error;
    }
} elseif (isset($_GET["id"])) {
    $id = $_GET["id"];
    $result = $conn->query("SELECT * FROM faq WHERE id=$id");

    if ($result->num_rows > 0) {
        $faq = $result->fetch_assoc();
    } else {
        echo "FAQ not found";
        exit;
    }
} else {
    echo "FAQ ID not provided";
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

<h2>Edit FAQ</h2>
<form method="post" action="">
  <input type="hidden" name="id" value="<?php echo $faq['id']; ?>">
  Question: <input type="text" name="question" value="<?php echo $faq['question']; ?>" required><br>
  Answer: <textarea name="answer" required><?php echo $faq['answer']; ?></textarea><br>
  <input type="submit" value="Submit">
</form>

<a href="../admin-index.php">
Back to Admin Dashboard</a>

</body>
</html>
