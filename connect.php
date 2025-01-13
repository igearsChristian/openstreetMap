<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize the input data
    $name = htmlspecialchars($_POST['name']);
    $category = htmlspecialchars($_POST['category']);
    $lat = htmlspecialchars($_POST['lat']);
    $long = htmlspecialchars($_POST['long']);
}

include("DB.php");

$sql = "INSERT INTO locations (name, category, lat, long_)
        VALUES ('$name','$category','$lat','$long')";

if (mysqli_query($conn, $sql)) {
    header("Location: index.php");
    exit();}
?>