<?php 
    //MySQL Database connection 
    $configs = include("config.php");
    include "connect.php";
    
    // build JSON file for Activity HeatMap
    $sql = "SELECT epochDate, eventCount FROM vwHeatmapData";
    $result = $conn->query($sql);     
    $heatmapArray = array();   
    if ($result->num_rows == 0) { 
        echo "<p>No data</p>";
    } else {
        while($row = $result->fetch_assoc()) {
            $thisEpochDate = $row["epochDate"];
            $thisEventCount = $row["eventCount"];     
            $heatmapArray[$thisEpochDate] =  $thisEventCount;
        };
    }

    $fp = fopen('i/meetups.json', 'w');
    fwrite($fp, json_encode($heatmapArray, JSON_NUMERIC_CHECK));
    fclose($fp);
?>
<!DOCTYPE html>
<html>
  <head>
        <title><?php echo($configs->GROUP_NAME); ?> | Stats</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta property="og:image" content="<?php echo($configs->OG_IMAGE_URL); ?>" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo($configs->GROUP_NAME); ?> Stats" />
		<meta property="og:description" content="<?php echo($configs->GROUP_NAME); ?> Meetup Event Stats." />
		<meta property="og:url" content="<?php echo($configs->PAGE_URL); ?>" />
		<meta property="og:site_name" content="<?php echo($configs->GROUP_NAME); ?>" />
		<meta property="fb:app_id" content="<?php echo($configs->FACEBOOK_APP_ID); ?>" />
        <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
	    <link rel="stylesheet" type="text/css" href="i/coffee.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.6/d3.min.js" charset="utf-8"></script>
    
        <link rel="stylesheet" href="i/cal-heatmap.css" />
    <script type="text/javascript" src="i/cal-heatmap.js"></script>
    <script type="text/javascript" src="i/jquery-latest.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
  	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
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
      <span class="text-warning nav-link">Stats</span>
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

    <h3>Meetup Heatmap</h3> 
    <div id="heatmap-holder" style="padding-left:10px"> 
        <div id="cal-heatmap"></div>
    </div>  
            <script type="text/javascript">
                var cal = new CalHeatMap();
                cal.init({
                    itemSelector: "#cal-heatmap",
                    data: "i/meetups.json",
                    start: new Date("2006-07-15"),
                    considerMissingDataAsZero: true,
                    range: 13,
                    domain: "year",
                    subDomain: "month",
                    cellSize: 20,
                    tooltip: true,
                    verticalOrientation: true,
                    domainDynamicDimension: true,
                    previousSelector: "#cal-heatmap-PreviousDomain-selector",
                    nextSelector: "#cal-heatmap-NextDomain-selector",
                    label: {
                        position: "right",
                        offset: {
                            x: 0,
                            y: 15
                        }
                    },
                    legendColors: {
                        min: "#efefef",
                        max: "steelblue",
                        empty: "white"
                    },
                    legend: [1, 5, 10, 15],
                    onClick: function(date, nb) {
                        var monthPage = "monthly.php?d=" + date.getFullYear() + date.getMonth();
                        window.location.href = monthPage;
	                }
                });
            </script>
    <h3 style="padding-top:20px">Events by Day of the Week</h3>

    <table id="myTable" class="tablesorter">
      <thead>
        <tr>
          <th class="col0">Day of Week</th>
          <th class="col1">Events</th>
          <th class="col2">Percentage</th>          
        </tr>
      </thead>
      <tbody>
      <?php
        $sql = "SELECT DAYOFWEEK(eventDate) as DayNumber, DAYNAME(eventDate) as DayFriendly, 
            COUNT(*) AS EventsThatDay, 
            ROUND((COUNT(*)/ SUM_Table.TotalEvents)* 100,1) AS EventsThatDayPercent,
            SUM_Table.TotalEvents
        FROM eventsclean AS EVC
        INNER JOIN (SELECT COUNT(*) as TotalEvents FROM eventsclean ) AS SUM_Table
        GROUP BY DAYOFWEEK(eventDate), DAYNAME(eventDate)
        ORDER BY DayNumber";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<tr>";                
                echo "<td>". $row["DayFriendly"]. "</td>";
                echo "<td>". $row["EventsThatDay"]. "</td>";
                echo "<td>". $row["EventsThatDayPercent"]. "</td>";
                echo "</tr>";		
            }
        } else {
            echo "<tr><td>Database Error</td></tr>";
        }       

        ?>
      </tbody>
    </table>


    <h3 style="padding-top:20px">Events by Month</h3>

    <table id="myTable" class="tablesorter">
      <thead>
        <tr>
          <th class="col0">Month</th>
          <th class="col1">Events</th>
          <th class="col2">Percentage</th>          
        </tr>
      </thead>
      <tbody>
      <?php
        $sql = "SELECT MONTH(eventDate) as MonthNumber, MONTHNAME(eventDate) as MonthFriendly, 
                COUNT(*) AS EventsThatMonth, 
                ROUND((COUNT(*)/ SUM_Table.TotalEvents)* 100,1) AS EventsThatMonthPercent,
                SUM_Table.TotalEvents
            FROM eventsclean AS EVC
            INNER JOIN (SELECT COUNT(*) as TotalEvents FROM eventsclean ) AS SUM_Table
            GROUP BY MONTH(eventDate), MONTHNAME(eventDate)
            ORDER BY MonthNumber";
        
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<tr>";                
                echo "<td>". $row["MonthFriendly"]. "</td>";
                echo "<td>". $row["EventsThatMonth"]. "</td>";
                echo "<td>". $row["EventsThatMonthPercent"]. "</td>";
                echo "</tr>";		
            }
        } else {
            echo "<tr><td>Database Error</td></tr>";
        }
        
        ?>
      </tbody>
    </table>     

    <h3 style="padding-top:20px">Events by Year</h3>
    <table id="myTable" class="tablesorter">
      <thead>
        <tr>
          <th class="col0">Year</th>
          <th class="col1">Events</th>                   
        </tr>
      </thead>
      <tbody>
      <?php
        $sql = "SELECT YEAR(eventDate) as YearNumber,  
                COUNT(*) AS EventsThatYear
            FROM eventsclean AS EVC
            GROUP BY YEAR(eventDate)
            ORDER BY YearNumber";
        $result = $conn->query($sql);

        $total_events = 0;
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<tr>";                
                echo "<td>". $row["YearNumber"]. "</td>";
                echo "<td>". $row["EventsThatYear"]. "</td>";               
                echo "</tr>";		
                $total_events += $row["EventsThatYear"];
            }
            echo "<tr><td><strong>Total</strong></td><td><strong>".$total_events."</strong></td></tr>";
        } else {
            echo "<tr><td>Database Error</td></tr>";
        }
        	$conn->close(); 
        ?>
      </tbody>
    </table>     
    <p><a href="cronMeetup.php" rel="nofollow" style="color: #ffffff;">Update Now</a></p>
    </div>
  </body>
</html>