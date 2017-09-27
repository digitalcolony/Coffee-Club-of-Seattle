<?php 
	//MySQL Database connection
	$configs = include("config.php");
	include "connect.php";
?>
<!DOCTYPE html>
<html>
  <head>
    <title><?php echo($configs->GROUP_NAME); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta property="og:image" content="<?php echo($configs->OG_IMAGE_URL); ?>" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo($configs->GROUP_NAME); ?> Venue List" />
		<meta property="og:description" content="<?php echo($configs->GROUP_DESCRIPTION); ?>" />
		<meta property="og:url" content="<?php echo($configs->PAGE_URL); ?>" />
		<meta property="og:site_name" content="<?php echo($configs->GROUP_NAME); ?>" />
		<meta property="fb:app_id" content="<?php echo($configs->FACEBOOK_APP_ID); ?>" />
		<link rel="stylesheet" type="text/css" href="i/coffee.css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script> 
		<script type="text/javascript" src="i/jquery.tablesorter.js"></script> 
		<script type="text/javascript">
			// setup table sort 
			$(document).ready(function() 
						{ 
								$("#myTable").tablesorter(); 
						} 
				); 			
		</script>
  </head>
  <body>
	<p>Venue report for the <a href="https://www.meetup.com/<?php echo($configs->GROUP_URLNAME); ?>/">
		<?php echo($configs->GROUP_NAME); ?></a>. <button>Show Inactive</button>
	<a href="map.php">View Map</a> | <a href="stats.php">View Stats</a></p>
		
    <table id="myTable" class="tablesorter">
      <thead>
        <tr>
          <th class="col0">Venue</th>
          <th class="col1">Location</th>
          <th class="col2">First Visit</th>
          <th class="col3">Last Visit</th>
          <th class="col4">Total </th>
        </tr>
      </thead>
      <tbody>
<?php
	$sql = "SELECT VC.venueName AS Venue, VC.venueID, VC.City, VC.venueStatus AS Status, 
		DATE_FORMAT(MIN(eventDate),'%m-%d-%Y') AS 'First', 
		DATE_FORMAT(MAX(eventDate),'%m-%d-%Y') AS 'Last', COUNT(*) as 'Total'
	FROM venuesclean VC
		INNER JOIN venuesmapped VM ON VC.venueID = VM.venueIDmapped
		INNER JOIN eventsclean E ON E.venueID = VM.venueID
	WHERE VC.venueType = 'Coffee'	
	GROUP BY VC.venuename 
	ORDER BY COUNT(*) DESC";

	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			echo "<tr class='". $row["Status"]. "'>";
			echo "<td class='col0'><a href='cafe.php?id=".$row["venueID"]."'>". $row["Venue"]. "</a></td>";
			echo "<td class='col1'>". $row["City"]. "</td>";
			echo "<td class='col2'>". $row["First"]. "</td>";
			echo "<td class='col3'>". $row["Last"]. "</td>";
			echo "<td class='col4'>". $row["Total"]. "</td>";
			echo "</tr>";		
		}
	} else {
		echo "<tr><td>0 results</td></tr>";
	}
	$conn->close();
?>
      </tbody>
    </table>
<p><?php echo $result->num_rows; ?> venues.</p>
	<script>
		// toggle visibility of Inactive Cafes 
		$( "button" ).click(function() {			
			$( ".Inactive" ).toggle();
			$(this).text(function(i, text){
					return text === "Display Inactive" ? "Hide Inactive" : "Display Inactive";
			})
		});
	</script>		    
  </body>
</html>