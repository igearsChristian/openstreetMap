<?php
$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "test_DB";


try {
    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

}
catch(exception) {
    echo "Could not connect!";
}

if ($conn) {
    echo "You are connected! . <br>";
}

$truncate_sql = "TRUNCATE TABLE locations";
mysqli_query($conn, $truncate_sql);

$inject_sql = "INSERT INTO locations (name, lat, long_)
VALUES 
    ('iGears Technology Ltd', 22.357929802706558, 114.13166951186284), 
    ('Airside Shopping Mall', 22.331685749828214, 114.19804471409547)";

mysqli_query($conn, $inject_sql);
?>