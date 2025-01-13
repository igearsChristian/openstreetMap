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
        
        var satelliteLayer = L.tileLayer(
        "https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png",
        {
          maxZoom: 17,
          attribution: "© OpenTopoMap contributors",
        }
      );

        streetLayer.addTo(map);

        var baseLayers = {
            "Street Map": streetLayer,
            "Satellite Map": satelliteLayer,
        };
        
        
        //marker groups
        var IT_group = L.layerGroup().addTo(map);
        var Commerical_group = L.layerGroup().addTo(map);

        var overlays = {
        "IT Companies": IT_group,
        Commercial: Commerical_group,
        };

        L.control.layers(baseLayers, overlays).addTo(map);
    </script>


    <?php
        include("DB.php");

        $sql = "SELECT * FROM locations";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            echo "<script>";
            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['category'] == 'Tech') {
                    echo "L.marker([{$row['lat']}, {$row['long_']}]).addTo(IT_group).bindPopup('{$row['name']}');";
                } else {
                    echo "L.marker([{$row['lat']}, {$row['long_']}]).addTo(Commerical_group).bindPopup('{$row['name']}');";
                }
            }
            echo "</script>";
        } else {
            echo "<script>console.log('Nothing found!');</script>";
        }
        
        mysqli_close($conn);
    ?>
</body>
</html>
