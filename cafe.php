<?php 
	//MySQL Database connection 
	$configs = include("./src/php/config.php");
	include "./src/php/connect.php";
	header("Cache-Control: max-age=14400"); //4 hours (60sec * 60min * 4)

	// Get VenueID from QueryString, then test for Integer
	$venueID = $_GET['id'];
	if(is_numeric($venueID)) 
	{
		$sql = "SELECT venueName, address_1, address_2, city, state, country, lat, lon, venueType, venueStatus  
				FROM cc_venues WHERE venueType='Standard' AND cc_venueID = ". $venueID;	
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			$row = $result->fetch_assoc();
			$thisVenue = $row["venueName"];
			$thisAddress = $row["address_1"]. " ". $row["address_2"];
			$thisCity = $row["city"];
			$thisState = $row["state"];
			$thisCountry = $row["country"];
			$thisLat = $row["lat"];
			$thisLon = $row["lon"];
			$thisVenueType = $row["venueType"];
			$thisVenueStatus = $row["venueStatus"];
		} else {
			echo "Could not find Venue. Return to <a href='.'>summary report</a>";
			exit();
		}

	} else {
		echo "Could not find Venue! Return to <a href='.'>summary report</a>";
		exit();
	}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title><?php echo($thisVenue); ?> - <?php echo($configs->GROUP_NAME); ?></title>    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta property="og:image" content="<?php echo($configs->OG_IMAGE_URL); ?>" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo($configs->GROUP_NAME); ?> Venue Detail" />
		<meta property="og:description" content="<?php echo($configs->GROUP_DESCRIPTION); ?>" />
		<meta property="og:url" content="<?php echo($configs->PAGE_URL); ?>" />
		<meta property="og:site_name" content="<?php echo($configs->GROUP_NAME); ?>" />
		<meta property="fb:app_id" content="<?php echo($configs->FACEBOOK_APP_ID); ?>" />
		<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" type="text/css" href="/src/css/coffee.css?v=<?php echo($configs->CSS_VERSION); ?>">
		<meta name="robots" content="noindex, follow">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<script src="/src/js/jquery-3.3.1.min.js"></script>	
		<link href="/src/css/bootstrap.min.css" rel="stylesheet">
		<script src="/src/js/bootstrap.min.js"></script>	
	</head>
<body>
<?php
	// insert nav menu
	$currentPage = "Cafe";
	include("./src/php/menu.php");
?>
<div class="container-fluid" style="padding-top:80px">
  <h3><?php echo $thisVenue ?></h3>
  
  <p><?php echo $thisAddress ?> - <?php echo $thisCity ?>, <?php echo $thisState ?>  <?php echo $thisCountry ?> (<?php echo $thisVenueStatus ?>)</p>
  
    <table class="tablesorter table table-hover" style="width: auto !important;">
      <thead>
        <tr style="background-color:#d3d3d3" class="bg-muted">
          <th></th>
          <th>Event</th>
          <th>Date</th>          
        </tr>
      </thead>
      <tbody>
 		<?php 
	 		$sql = "SELECT eventLink, eventName, DATE_FORMAT(eventDate,'%m-%d-%Y') AS eventDateFormat
			 				FROM vwCC_AllEvents WHERE cc_venueID = ". $venueID. " ORDER BY eventDate DESC";
 			$result = $conn->query($sql);
 			$j = 0;
 			
 			if ($result->num_rows > 0) {
 				// output data of each row
 				while($row = $result->fetch_assoc()) {
 					echo "<tr>";
 					echo "<td>". ++$j . "</td>";
 					echo "<td class='col0'><a href='".$row["eventLink"]."' target='_blank'>". htmlentities($row["eventName"]). "</a></td>";
 					echo "<td class='col1'>". $row["eventDateFormat"]. "</td>";
 					echo "</tr>";
 				}
 			}
 			else {
 				echo "<tr><td colspan='2'>0 results</td></tr>";
 			}
 			$conn->close(); 			
 		?>
      </tbody>
    </table>    
		</div>
<?php 
	include_once("./src/php/google.php");
?>
  </body>
</html>