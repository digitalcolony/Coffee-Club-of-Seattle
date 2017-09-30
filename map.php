<?php 
    //MySQL Database connection 
    $configs = include("config.php");
	include "connect.php";
?>
<!DOCTYPE html>
<html>
  <head>
    <title><?php echo($configs->GROUP_NAME); ?> | Map</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta property="og:image" content="<?php echo($configs->OG_IMAGE_URL); ?>" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?php echo($configs->GROUP_NAME); ?> Map" />
    <meta property="og:description" content="<?php echo($configs->GROUP_NAME); ?> <?php echo($configs->PAGE_URL); ?>" />
    <meta property="og:url" content="<?php echo($configs->PAGE_URL); ?>" />
    <meta property="og:site_name" content="<?php echo($configs->GROUP_NAME); ?>" />
    <meta property="fb:app_id" content="<?php echo($configs->FACEBOOK_APP_ID); ?>" />  
    <link rel="stylesheet" type="text/css" href="i/coffee.css?1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <body>
    <p><a href=".">Venue Report</a> | <a href="stats.php">View Stats</a></p>
    <p>Map of Active Venues visited by the <?php echo($configs->GROUP_NAME); ?>. Total visits appears on marker. Meetup only records the initial meeting spot,
    so multi-location Meetups are not included in the dataset.</p> 
    <div id="map"></div>
    <!-- Google Maps JS API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBs8dGa-nxjxPn_wE2VfkxeagY-TO8rQSU"></script>
  
    <!-- GMaps Library -->
    <script src="i/gmaps.js"></script>
    <script>
      /* Map Object */
      var mapObj = new GMaps({
        el: '#map',
        lat: <?php echo($configs->MAP_START_LAT); ?>,
        lng: <?php echo($configs->MAP_START_LON); ?>,
        zoom: 11
      });

    <?php 
        $sql = "SELECT venue, venueID, lat, lon, first, last, total 
            FROM vwAllStandardVenues
            WHERE status = 'Active'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "var m = mapObj.addMarker({";
                echo "  lat: ".$row['lat'].",";
                echo "  lng: ".$row['lon'].",";
                echo "  label: '".$row['total']."',";
                echo " title: '".addslashes($row['venue'])."', ";
                echo " infoWindow: { ";
                echo " content: '<h4>".addslashes($row['venue'])."</h4> "; 
                echo " <div><a href=\"cafe.php?id=".$row['venueID']."\">Last: ".$row['last']."</a></div>', ";
                echo " maxWidth: 100 } ";
                echo "});";               
            } 
        }
        $conn->close();
?>
    </script>

  </body>
</html>