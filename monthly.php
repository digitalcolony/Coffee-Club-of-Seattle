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
    <title>Monthly Report for the Coffee Club of Seattle</title>    
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
		<link rel="stylesheet" type="text/css" href="i/coffee.css">
		<meta name="robots" content="noindex, follow">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>

  <body>
  <p><a href=".">Venue Report</a>
  <h3>Meetups for <?php echo $monthName." ".$year ?> </h3>
  
 <!-- <form action="monthly">
      <select name="month" id="month">
          <option value="1">January</option>
          <option value="2">February</option>
          <option value="3">March</option>
          <option value="4">April</option>
          <option value="5">May</option>
          <option value="6">June</option>
          <option value="7">July</option>
          <option value="8">August</option>
          <option value="9">September</option>
          <option value="10">October</option>
          <option value="11">November</option>
          <option value="12">December</option> 
      </select>
  </form> -->
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
  </body>
</html>