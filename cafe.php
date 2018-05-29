<?php 
	//MySQL Database connection 
	$configs = include("config.php");
	include "connect.php";

	// Get VenueID from QueryString, then test for Integer
	$venueID = $_GET['id'];
	if(is_numeric($venueID)) 
	{
		$sql = "SELECT venueName, address_1, address_2, city, state, country, lat, lon, venueType, venueStatus  
				FROM venuesclean WHERE venueID = ". $venueID;	
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

<html>
  <head>
    <title><?php echo($thisVenue); ?> - <?php echo($configs->GROUP_NAME); ?></title>    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta property="og:image" content="<?php echo($configs->OG_IMAGE_URL); ?>" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo($configs->GROUP_NAME); ?> Venue List" />
		<meta property="og:description" content="<?php echo($configs->GROUP_DESCRIPTION); ?>" />
		<meta property="og:url" content="<?php echo($configs->PAGE_URL); ?>" />
		<meta property="og:site_name" content="<?php echo($configs->GROUP_NAME); ?>" />
		<meta property="fb:app_id" content="<?php echo($configs->FACEBOOK_APP_ID); ?>" />
		<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
		<link rel="stylesheet" type="text/css" href="i/coffee.css">
		<meta name="robots" content="noindex, follow">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
  	
  </head>

  <body>
	<nav id="topNav" class="navbar navbar-expand-md bg-dark navbar-dark fixed-top">
	 	<a class="navbar-brand text-white" href="/"><?php echo($configs->GROUP_NAME); ?></a>

		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
   		 <span class="navbar-toggler-icon"></span>
  	</button>

	<div class="collapse navbar-collapse" id="collapsibleNavbar">
	 <ul class="navbar-nav">
	 <li class="nav-item">
     <a class="nav-link text-info" href="/">Venues</a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-info" href="map.php">Map</a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-info" href="stats.php">Stats</a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-info" href="leads.php">Leads</a>
    </li>
		<li class="nav-item">
      <a class="nav-link text-info" target="_blank" href="https://www.meetup.com/<?php echo($configs->GROUP_URLNAME); ?>/">Meetup</a>
    </li>
		<li class="nav-item">
      <a class="nav-link text-info" target="_blank" href="https://github.com/digitalcolony/Coffee-Club-of-Seattle">GitHub</a>
    </li>
  </ul>			
	</div>
</nav>
<div class="container-fluid" style="padding-top:80px">
  <h3><?php echo $thisVenue ?></h3>
  
  <p><?php echo $thisAddress ?> - <?php echo $thisCity ?>, <?php echo $thisState ?>  <?php echo $thisCountry ?> (<?php echo $thisVenueStatus ?>)</p>
  
    <table>
      <thead>
        <tr>
          <th></th>
          <th>Event</th>
          <th>Date</th>          
        </tr>
      </thead>
      <tbody>
 		<?php 
	 		$sql = "SELECT eventLink, eventName, DATE_FORMAT(eventDate,'%m-%d-%Y') AS eventDateFormat
			 				FROM vwAllEvents WHERE venueID = ". $venueID. " ORDER BY eventDate DESC";
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
  </body>
</html>