<?php 
	//MySQL Database connection 
	$configs = include("./src/php/config.php");
    include "./src/php/connect.php";
    header("Cache-Control: max-age=14400"); //4 hours (60sec * 60min * 4)

	// Get date from QueryString
    $date = (isset($_GET['d']) ? $_GET['d'] : null);
	if(is_numeric($date) && (strlen($date) == 5 OR strlen($date) ==6) && $date != null) 
	{
        $year = substr($date,0,4);
        $month = substr($date,4,strlen($date)-4);
        $month = $month + 1; // moving JS 0-based month to mySQL 1-based
    } else {
        $year = date("Y");
        $month = date("m");
    }

    $fullDate = $year."-".$month."-1";
    $monthName = date('F', mktime(0, 0, 0, $month, 10)); 
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Monthly Report for the <?php echo($configs->GROUP_NAME); ?></title>    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta property="og:image" content="<?php echo($configs->OG_IMAGE_URL); ?>" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo($configs->GROUP_NAME); ?> Monthly Report" />
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
	$currentPage = "Monthly";
	include("./src/php/menu.php");
?>
<div class="container-fluid" style="padding-top:80px">
  <h3>Meetups for <?php echo $monthName." ".$year ?> </h3>
  <table class="table table-hover" style="width: auto !important;">
      <thead>
      <tr style="background-color:#d3d3d3" class="bg-muted">
          <th></th>
          <th>Venue</th>
          <th>Date</th>          
        </tr>
      </thead>
      <tbody>
<?php
    $query = "CALL spGetCCEventsByMonth ('".$fullDate."')";
    //run the store proc

    $result = mysqli_query($conn, $query) or die("Query fail: " . mysqli_error());
    $j = 0;
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>". ++$j . "</td>";
            echo "<td><a href='cafe.php?id=".$row["cc_venueID"]."'>". $row["venueName"] ."</a></td>";
            echo "<td class='col1'><a href='".$row["eventLink"]."' target='_blank'>". $row["eventDateFormat"]. "</a></td>";
            echo "</tr>";
        }
    }
    else {
        echo "<tr><td colspan='4'>0 results</td></tr>";
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