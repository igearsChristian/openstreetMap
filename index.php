<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <!-- bootstrap css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
      :root {
          --map-top: 7vh;
          --control_panel-width: 350px;
          --control_panel-height: 100vh;
      }

      #map {
        height: var(--control_panel-height); 
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
          height: var(--control_panel-height);  /* Full height of the viewport */
          width: var(--control_panel-width);   /* Set a width for the control panel */
          padding: 20px;
          background-color: #fff;
          box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
          transition: left 0.3s ease; /* Sliding effect */
      }
      .control-panel.active {
        left: calc(-0.98 * var(--control_panel-width)); 
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
<?php 
    include("header.php");
?>
<body>

<!-- <div id="header">
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><img class="logo" src="img/igears3.png" alt="Bootstrap" width="170" height="60"/></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="#">Interactive Map</a></li>
                <li class="nav-item"><a class="nav-link" href="text_form.php">Database search</a></li>
                <li class="nav-item"><a class="nav-link" href="#">About us</a></li>
            </ul>
            <form class="d-flex" role="search">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"/>
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>
    </div>
</nav>
</div> -->
    <div id="map"></div>
    <div class="leaflet-control info-panel" id="infoPanel">
      <h3>Marker Information</h3>
      <img id="infoImage" class="info-image" src="" alt="Marker Image" />
      <p id="infoText">Click on a marker to see details.</p>
    </div>

    <div class="leaflet-control control-panel" id="controlPanel">
    <h2>Control Panel</h2> </br>
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

    </br><h3>Select Marker Group:</h3>
    <h4>Industry</h4>
    <form id="markerSelector">
        <div>
            <div>
                <input type="checkbox" id="toggleIT" checked />
                <label for="toggleIT">Show IT companies</label>
            </div>
            <div>
                <input type="checkbox" id="toggleCommerical" checked />
                <label for="toggleCommerical">Show Commercial buildings</label>
            </div>
        </div>
    </form>
    
    </br>
    <button type="button" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        Add new locations
    </button> 
    </br></br>
    <form action="DB_init.php" method="post">
        <input type="submit" value="Reset the map" />
    </form>


</div>

    <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Enter a new location</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
            <form action="connect.php" method="post">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="category" class="form-label">Category</label>
                    <input type="text" class="form-control" id="category" name="category" required>
                </div>
                <div class="mb-3">
                    <label for="new_lat" class="form-label">Latitude</label>
                    <input type="text" class="form-control" id="lat" name="lat" required>
                </div>
                <div class="mb-3">
                    <label for="new_long" class="form-label">Longitude</label>
                    <input type="text" class="form-control" id="long" name="long" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            </form>
        
        </div>
    </div>
    </div>


    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
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
    <script src="logic.js"></script>
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

    <!-- bootstrap js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
