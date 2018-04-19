<?php
    // This page will be executed by a cron job once a day. 
    // It will generate a JSON file for the Activity Heatmap
    // It will run after the cronMeetup.php 

    //MySQL Database connection 
    $configs = include("config.php");
    include "connect.php";
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Nightly Meetup Job 2 - Creating JSON File </title>
    <meta name="robots" content="noindex, nofollow">
  </head>
  <body>
<?php

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

$conn->close();
?>

    <p>Cron Job 2 to Meetup has completed!</p>
    <p>Return to <a href="<?php echo($configs->PAGE_URL); ?>">report</a>.</p>
  </body>
</html>
