<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Map with Markers</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>

      #map {
        height: 90vh;
        width: 100%;
        margin: 0;
        padding: 0;
        position: absolute;
        top: 12vh; 
      }
      .info-panel {
        position: absolute;
        top: 0;
        right: -350px; /* Start hidden outside the viewport */
        width: 300px;
        height: 100%;
        background: rgba(255, 255, 255, 0.9);
        border-left: 1px solid #ccc;
        box-shadow: -2px 0 5px rgba(0, 0, 0, 0.5);
        transition: right 0.3s ease; /* Sliding effect */
        padding: 20px;
      }
      .info-panel.active {
        right: 0; /* Slide in */
      }
      .info-image {
        width: 100%; /* Responsive width */
        height: auto; /* Maintain aspect ratio */
        margin-bottom: 10px; /* Spacing below the image */
      }
    </style>
</head>
<body>
<div id="header">
    <h1>iGears OpenStreetMap</h1>
</div>
    <div id="map"></div>
    <div class="leaflet-control info-panel" id="infoPanel">
      <h3>Marker Information</h3>
      <img id="infoImage" class="info-image" src="" alt="Marker Image" />
      <p id="infoText">Click on a marker to see details.</p>
    </div>

    <div style="display: flex; align-items: center;">
    <form action="connect.php" method="post">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required />

            <label for="name">Category:</label>
            <input type="text" id="category" name="category" required />

            <label for="lat">Latitude:</label>
            <input type="text" id="lat" name="lat" required />

            <label for="long">Longitude:</label>
            <input type="text" id="long" name="long" required />

            <input type="submit" value="Submit" />
            
        </form>

    <form action="DB_init.php" method="post">
        <input type="submit" value="Reset" />
        </form>
    </div>
    
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var map = L.map("map").setView([22.31380306893066, 114.1755544938077], 13);
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
        
        var redIcon = new L.Icon({
            iconUrl: './img/spot.png',
            iconSize: [31, 31], 

            shadowUrl: './img/marker-shadow.png', 
            shadowSize: [41, 41] 
        });

        var blueIcon = new L.Icon({
            iconUrl: './img/spot4.png', 
            iconSize: [31, 31],
            shadowUrl: './img/marker-shadow.png', 
            shadowSize: [41, 41]
        });

        //marker groups
        var IT_group = L.layerGroup().addTo(map);
        var Commerical_group = L.layerGroup().addTo(map);

        var overlays = {
        "IT Companies": IT_group,
        Commercial: Commerical_group,
        };

        L.control.layers(baseLayers, overlays).addTo(map);

              // Slidable panel logic
      var infoPanel = document.getElementById("infoPanel");
      var infoText = document.getElementById("infoText");
      var infoImage = document.getElementById("infoImage");

      function showInfoPanel(text) {
        if (infoPanel.classList.contains("active")) {
          infoPanel.classList.remove("active");
        } else {
          infoText.innerHTML = text;
          if (text == "iGears Technology Ltd") {
            infoImage.src = './img/igears.jpg';
          }
          else if (text == "Airside Shopping Mall") {
            infoImage.src = './img/Airside Shopping Mall.jpeg';
          }
          infoPanel.classList.add("active");
        }
      }

    </script>


    <?php
        include("DB.php");
        // include("DB_init.php");
        $sql = "SELECT * FROM locations";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            echo "<script>";
            while ($row = mysqli_fetch_assoc($result)) {
                if ($row['category'] == 'Tech') {
                    echo "
                    var marker = L.marker([{$row['lat']}, {$row['long_']}], { icon: redIcon }).addTo(IT_group).bindPopup('{$row['name']}');
                    marker.on('click', function () {
                        showInfoPanel('{$row['name']}');
                    });
                ";
                } else {
                    echo "
                    var marker = L.marker([{$row['lat']}, {$row['long_']}], { icon: blueIcon }).addTo(Commerical_group).bindPopup('{$row['name']}');
                    marker.on('click', function () {
                        showInfoPanel('{$row['name']}');
                    });
                ";
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
