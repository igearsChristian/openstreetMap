<?php 
include("DB.php");

$truncate_sql = "TRUNCATE TABLE locations";
mysqli_query($conn, $truncate_sql);

$inject_sql = "INSERT INTO locations (name, category, lat, long_)
VALUES 
    ('iGears Technology Ltd', 'Tech', 22.357929802706558, 114.13166951186284), 
    ('Airside Shopping Mall', 'Commerical', 22.331685749828214, 114.19804471409547)";

if (mysqli_query($conn, $inject_sql)) {
    header("Location: index.php");
    exit();}

?>