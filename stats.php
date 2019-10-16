<?php 
    //MySQL Database connection 
    $configs = include("./src/php/config.php");
    include "./src/php/connect.php";
    header("Cache-Control: max-age=14400"); //4 hours (60sec * 60min * 4)
    // build JSON file for Activity HeatMap
    $sql = "SELECT epochDate, eventCount FROM vwCC_HeatmapData";
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
    $fp = fopen('./src/data/meetups.json', 'w');
    fwrite($fp, json_encode($heatmapArray, JSON_NUMERIC_CHECK));
    fclose($fp);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title><?php echo($configs->GROUP_NAME); ?> | Stats</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta property="og:image" content="<?php echo($configs->OG_IMAGE_URL); ?>" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="<?php echo($configs->GROUP_NAME); ?> Stats" />
    <meta property="og:description" content="<?php echo($configs->GROUP_NAME); ?> Meetup Event Stats." />
    <meta property="og:url" content="<?php echo($configs->PAGE_URL); ?>" />
    <meta property="og:site_name" content="<?php echo($configs->GROUP_NAME); ?>" />
    <meta property="fb:app_id" content="<?php echo($configs->FACEBOOK_APP_ID); ?>" />
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <link rel="stylesheet" type="text/css" href="/src/css/coffee.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/src/css/cal-heatmap.css" />
    <!-- Use v 3.5.6 of d3. The 5.5 version breaks heatmap. -->
    <script src="/src/js/d3.3.5.6.min.js"></script> 
     <script src="/src/js/jquery-3.3.1.min.js"></script>	
    <link href="/src/css/bootstrap.min.css" rel="stylesheet">
    <script src="/src/js/bootstrap.min.js"></script>  
    <script src="/src/js/cal-heatmap.js"></script> 
    
</head>
<body>
  <?php
	// insert nav menu
	$currentPage = "Stats";
  include("./src/php/menu.php");
?>
<div class="container-fluid" style="padding-top:80px">
    <h3>Meetup Heatmap</h3> 
    <div id="heatmap-holder" style="padding-left:10px"> 
        <div id="cal-heatmap"></div>
    </div>  
        <script type="text/javascript">
            var cal = new CalHeatMap();
            // Define the rows of the Heatmap (range) as Current Year - 2006 (+1)
            var d = new Date();
            var n = d.getFullYear();
            var range = n - 2006 + 1;
            cal.init({
                itemSelector: "#cal-heatmap",
                data: "/src/data/meetups.json",
                start: new Date("2006-07-15"),
                considerMissingDataAsZero: true,
                range: range,
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

    <table id="myTable" class="tablesorter table table-condensed" style="width: auto !important;">
      <thead>
        <tr style="background-color:#d3d3d3" class="bg-muted">
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
        FROM cc_events AS EVC
        INNER JOIN (SELECT COUNT(*) as TotalEvents FROM cc_events) AS SUM_Table
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

    <table id="myTable" class="tablesorter table table-condensed" style="width: auto !important;">
      <thead>
        <tr style="background-color:#d3d3d3" class="bg-muted">
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
            FROM cc_events AS EVC
            INNER JOIN (SELECT COUNT(*) as TotalEvents FROM cc_events ) AS SUM_Table
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
    <table id="myTable" class="tablesorter table table-condensed" style="width: auto !important;">
      <thead>
        <tr style="background-color:#d3d3d3" class="bg-muted">
          <th class="col0">Year</th>
          <th class="col1">Events</th>                   
        </tr>
      </thead>
      <tbody>
      <?php
        $sql = "SELECT YEAR(eventDate) as YearNumber,  
                COUNT(*) AS EventsThatYear
            FROM cc_events AS EVC
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
    </div>
<?php 
    include_once("./src/php/google.php");
?>
  </body>
</html>