<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Map with Markers</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map {
            height: 100vh;
            width: 100%;
        }
    </style>
</head>
<body>
    <div id="map"></div>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var map = L.map("map").setView([22.2793278, 114.1628131], 13);
        var streetLayer = L.tileLayer(
            "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
            {
                maxZoom: 19,
                attribution: "© OpenStreetMap contributors",
            }
        );
        streetLayer.addTo(map);

        var markers = L.layerGroup().addTo(map);
    </script>

    <?php
        include("DB.php");
        $truncate_sql = "TRUNCATE TABLE locations";
        mysqli_query($conn, $truncate_sql);

        $inject_sql = "INSERT INTO locations (name, lat, long_)
        VALUES 
            ('iGears Technology Ltd', 22.357929802706558, 114.13166951186284), 
            ('Airside Shopping Mall', 22.331685749828214, 114.19804471409547)";

        mysqli_query($conn, $inject_sql);

        $sql = "SELECT * FROM locations";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            echo "<script>";
            while ($row = mysqli_fetch_assoc($result)) {
                // Output JavaScript to create markers
                echo "L.marker([{$row['lat']}, {$row['long_']}]).addTo(markers).bindPopup('{$row['name']}');";
            }
            echo "</script>";
        } else {
            echo "<script>console.log('Nothing found!');</script>";
        }
        
        mysqli_close($conn);
    ?>
</body>
</html>