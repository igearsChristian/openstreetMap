<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
      :root {
          --map-top: 12vh;
          --control_panel-width: 200px;
      }

      #map {
        height: 100vh; 
        width: 100%;
        margin: 0;
        padding: 0;
        position: fixed;
        top: var(--map-top); 
      }
      .info-panel {
        position: fixed;
        top: 0;
        right: -350px; 
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
      .control-panel {
          position: fixed;  /* Keep it fixed on the left side */
          top: var(--map-top);         /* Align to the top */
          left: 0;         /* Align to the left */
          height: 100vh;  /* Full height of the viewport */
          width: var(--control_panel-width);   /* Set a width for the control panel */
          padding: 20px;
          background-color: #fff;
          box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
          transition: left 0.3s ease; /* Sliding effect */
      }
      .control-panel.active {
        left: calc(-1.2 * var(--control_panel-width)); 
      }
      .toggle-button {
          position: absolute; /* Position relative to the control panel */
          top: calc(1.5 * var(--map-top) + var(--map-top)); 
          right: -30px; /* Position outside the panel */
          background-color: #c00; /* Example button color */
          color: white;
          border: none;
          border-radius: 5px;
          padding: 100px 10px;
          cursor: pointer;
          transition: right 0.3s ease; /* Smooth transition */
      }
      .leaflet-control-layers {
          display: none; /* Hides the control */
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
            <label for=""><strong>Add a new Marker -></strong></label>
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

    <div class="leaflet-control control-panel" id="controlPanel">
      <h2>Control Panel</h2>
      <button id="toggleButton" class="toggle-button">→</button>
      <h3>Select a Map:</h3>
      <form id="mapSelector">
        <div>
          <input
            type="radio"
            id="streetMap"
            name="options"
            value="street"
            checked
          />
          <label for="streetMap">Street Map</label>
        </div>
        <div>
          <input
            type="radio"
            id="satelliteMap"
            name="options"
            value="satellite"
          />
          <label for="satelliteMap">Satellite Map</label>
        </div>
      </form>
    </div>


    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="logic.js"></script>
    <script>
        var map = L.map('map', {
        center: [22.31380306893066, 114.1755544938077],
        zoom: 13,
        zoomControl: false
        })

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
