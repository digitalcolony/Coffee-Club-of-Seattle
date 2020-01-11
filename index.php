<?php 
	//MySQL Database connection
	$configs = include("./src/php/config.php");
	include "./src/php/connect.php";
	header("Cache-Control: max-age=14400"); //4 hours (60sec * 60min * 4)
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title><?php echo($configs->GROUP_NAME); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta property="og:image" content="<?php echo($configs->OG_IMAGE_URL); ?>" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo($configs->GROUP_NAME); ?> Venue List" />
		<meta property="og:description" content="<?php echo($configs->GROUP_DESCRIPTION); ?>" />
		<meta property="og:url" content="<?php echo($configs->PAGE_URL); ?>" />
		<meta property="og:site_name" content="<?php echo($configs->GROUP_NAME); ?>" />
		<meta property="fb:app_id" content="<?php echo($configs->FACEBOOK_APP_ID); ?>" />
		<meta name="description" content="<?php echo($configs->GROUP_DESCRIPTION); ?>">
		<link rel="stylesheet" type="text/css" href="/src/css/coffee.css?v=<?php echo($configs->CSS_VERSION); ?>">
		<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<script src="/src/js/jquery-3.3.1.min.js"></script>	  
		<script type="text/javascript" src="/src/js/jquery.tablesorter.min.js"></script> 
		<script type="text/javascript">
			// setup table sort 
			$(document).ready(function() 
				{ 
						$("#myTable").tablesorter({
							sortList:[[3,1]]
						}); 
				} 
			); 			
		</script>
	  <link href="/src/css/bootstrap.min.css" rel="stylesheet">
  	<script src="/src/js/bootstrap.min.js"></script>
  </head>
  <body>
<?php
	// insert nav menu
	$currentPage = "Venues";
	include("./src/php/menu.php");
?>

<div class="container-fluid" style="padding-top:80px">

<div class="input-group">
  <input class="form-control col-sm-2"
  id="filterInput" placeholder="filter venues" autofocus>
</div>

    <table id="myTable" class="tablesorter table table-hover">
      <thead>
        <tr style="background-color:#d3d3d3" class="bg-muted">
          <th class="col0">Venue</th>
          <th class="col1">Location&nbsp;&nbsp;</th>
          <th class="col2">First Visit</th>
          <th class="col3">Last Visit</th>
          <th class="col4">Total&nbsp;&nbsp;&nbsp;</th>
        </tr>
      </thead>
      <tbody>
<?php
	$sql = "SELECT venueName, cc_venueID, city, venueStatus, eventFirst, eventLast, eventTotal FROM vwCC_StandardVenues ORDER BY eventLast DESC, venueName";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		// output data of each row
		while($row = $result->fetch_assoc()) {
			echo "<tr class='". $row["venueStatus"]. "' id=''>";
			echo "<td class='col0'><a href='cafe.php?id=".$row["cc_venueID"]."'>". $row["venueName"]. "</a></td>";
			echo "<td class='col1'>". $row["city"]. "</td>";
			echo "<td class='col2'>". $row["eventFirst"]. "</td>";
			echo "<td class='col3'>". $row["eventLast"]. "</td>";
			echo "<td class='col4'>". $row["eventTotal"]. "</td>";
			echo "</tr>";		
		}
	} else {
		echo "<tr><td>0 results</td></tr>";
	}
	$conn->close();
?>
      </tbody>
    </table>
		
<p><?php echo $result->num_rows; ?> venues (includes Inactive).</p>
	</div>
	<script src="/src/js/filter.js" defer></script>
<?php 
	include_once("./src/php/google.php");
?>
  </body>
</html>