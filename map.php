<?php 
    //MySQL Database connection 
    $configs = include("./src/php/config.php");
    include "./src/php/connect.php";
    header("Cache-Control: max-age=14400"); //4 hours (60sec * 60min * 4)
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title><?php echo($configs->GROUP_NAME); ?> | Map</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	  <meta property="og:image" content="<?php echo($configs->OG_IMAGE_URL); ?>" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?php echo($configs->GROUP_NAME); ?> Map" />
    <meta property="og:description" content="<?php echo($configs->GROUP_NAME); ?> <?php echo($configs->PAGE_URL); ?>" />
    <meta property="og:url" content="<?php echo($configs->PAGE_URL); ?>" />
    <meta property="og:site_name" content="<?php echo($configs->GROUP_NAME); ?>" />
    <meta property="fb:app_id" content="<?php echo($configs->FACEBOOK_APP_ID); ?>" />  
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <link rel="stylesheet" type="text/css" href="/src/css/coffee.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="/src/js/jquery-3.3.1.min.js"></script>	
    <link href="/src/css/bootstrap.min.css" rel="stylesheet">
    <script src="/src/js/bootstrap.min.js"></script>
  </head>
  <body>
<?php
	// insert nav menu
	$currentPage = "Map";
	include("./src/php/menu.php");
?>
<div class="container-fluid" style="padding-top:80px">

    <p>Map of Active Venues visited by the <?php echo($configs->GROUP_NAME); ?>. Total visits appears on marker. Meetup only records the initial meeting spot,
    so multi-location Meetups are not included in the dataset.</p> 
    <div id="map"></div>
    <!-- Google Maps JS API -->
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo($configs->GOOGLE_MAP_KEY); ?>"></script>
    
    <!-- GMaps Library -->
    <script src="/src/js/gmaps.js"></script>
    <script>
      /* Map Object */
      var mapObj = new GMaps({
        el: '#map',
        lat: <?php echo($configs->MAP_START_LAT); ?>,
        lng: <?php echo($configs->MAP_START_LON); ?>,
        zoom: 11
      });

    <?php 
        $sql = "SELECT venueName, cc_venueID, lat, lon, eventFirst, eventLast, eventTotal 
            FROM vwCC_StandardVenues
            WHERE venueStatus = 'Active'";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "var m = mapObj.addMarker({";
                echo "  lat: ".$row['lat'].",";
                echo "  lng: ".$row['lon'].",";
                echo "  label: '".$row['eventTotal']."',";
                echo " title: '".addslashes($row['venueName'])."', ";
                echo " infoWindow: { ";
                echo " content: '<h4>".addslashes($row['venueName'])."</h4> "; 
                echo " <div><a href=\"cafe.php?id=".$row['cc_venueID']."\">Last: ".$row['eventLast']."</a></div>', ";
                echo " maxWidth: 180 } ";
                echo "});";               
            } 
        }
        $conn->close();
?>
    </script>
</div>
<p></p>
<?php 
	include_once("./src/php/google.php");
?>
  </body>
</html>