<?php
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
                } else if ($row['category'] == 'Commerical'){
                    echo "
                    var marker = L.marker([{$row['lat']}, {$row['long_']}], { icon: blueIcon }).addTo(Commerical_group).bindPopup('{$row['name']}');
                    marker.on('click', function () {
                        showInfoPanel('{$row['name']}');
                    });
                ";
                } else if ($row['category'] == 'Art'){
                    echo "
                    var marker = L.marker([{$row['lat']}, {$row['long_']}], { icon: artIcon }).addTo(Art_group).bindPopup('<h3>{$row['name']}</h3> <img src=\"./img/artwork.jpg\"style=\"max-width: 100%; height: auto;\">');
                    marker.on('click', function () {
                        showInfoPanel('{$row['name']}');
                    });
                ";
                }
            }
            
            echo "</script>";
        } 
        mysqli_close($conn);
?>

<!--                     var marker = L.marker([{$row['lat']}, {$row['long_']}], { icon: artIcon }).addTo(Art_group).('<h1>{$row['name']}</h1>    <img src="./img/igears.jpg" />'); -->