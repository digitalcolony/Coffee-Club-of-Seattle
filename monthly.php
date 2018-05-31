<?php 
	//MySQL Database connection 
	$configs = include("config.php");
	include "connect.php";

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
<html>
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
		<link rel="stylesheet" type="text/css" href="i/coffee.css">
		<meta name="robots" content="noindex, follow">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>	  
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
  </head>
<body>
<?php
	// insert nav menu
	$currentPage = "Monthly";
	include("inc/menu.php");
?>
<div class="container-fluid" style="padding-top:80px">
  <h3>Meetups for <?php echo $monthName." ".$year ?> </h3>
  <table>
      <thead>
        <tr>
          <th></th>
          <th>Venue</th>
          <th>Date</th>          
        </tr>
      </thead>
      <tbody>
<?php
    $query = "CALL spGetEventsByMonth ('".$fullDate."')";
    //run the store proc

    $result = mysqli_query($conn, $query) or die("Query fail: " . mysqli_error());
    $j = 0;
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>". ++$j . "</td>";
            echo "<td><a href='cafe.php?id=".$row["venueID"]."'>". $row["venueName"] ."</a></td>";
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
	include_once("inc/google.php");
?>
  </body>
</html>