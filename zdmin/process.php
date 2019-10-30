<?php 
    $rootFolder = $_SERVER['DOCUMENT_ROOT'];
    $configFile = $rootFolder . "/src/php/config.php";
    $configs = include_once($configFile);
    include_once($rootFolder . "/src/php/connect.php");
 
	header("Cache-Control: max-age=14400"); //4 hours (60sec * 60min * 4)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="/src/js/jquery-3.3.1.min.js"></script>	 
    <link href="/src/css/bootstrap.min.css" rel="stylesheet">
  	<script src="/src/js/bootstrap.min.js"></script>
    <title>Coffee Club Admin</title>
</head>
<body>
<nav id="topNav" class="navbar navbar-expand-md bg-dark navbar-dark fixed-top">
    <a class="navbar-brand text-white" href="/"><?php echo($configs->GROUP_NAME); ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
	    <ul class="navbar-nav">
        <li class='nav-item'><a class='nav-link text-info' href='/zdmin/'>Queue</a></li>
        <li class='nav-item'><span class='text-warning nav-link'>Process</span></li>
        </ul>
    </div>
</nav>

<div class="container-fluid" style="padding-top:80px">
  <h3>Process </h3>
 

<?php
    // parse JSON file
    $eventID = $_GET['json'];
    $eventPageURL = "https://www.meetup.com/seattle-coffee-club/events/";
    $json_file = $rootFolder . "/src/data/events/" . $eventID . ".json";
    if(file_exists($json_file)){
        $json = file_get_contents($json_file);
        $jsonFileDateString = "This event was uploaded on <strong>" . date ("F d, Y", filemtime($json_file))."</strong>.";
        $decoded = json_decode($json);

        
        $eventName = (isset($decoded->name)) ? $decoded->name : "?";
        $eventDate = (isset($decoded->startDate)) ? $decoded->startDate : "?";
        $eventURL = (isset($decoded->url)) ? $decoded->url : $eventPageURL. $eventID ."/";

        $venueName = (isset($decoded->location->name))? $decoded->location->name: "?";
        $address_1 = (isset($decoded->location->address->streetAddress))? $decoded->location->address->streetAddress: "?";
        $city = (isset($decoded->location->address->addressLocality))? $decoded->location->address->addressLocality: "?";
        $state = (isset($decoded->location->address->addressRegion))? $decoded->location->address->addressRegion: "?";
        $zip = (isset($decoded->location->address->postalCode))? $decoded->location->address->postalCode: "?";
        $country = (isset($decoded->location->address->addressCountry))? $decoded->location->address->addressCountry: "?";
        $lat = (isset($decoded->location->geo->latitude))? $decoded->location->geo->latitude: "?";
        $lon = (isset($decoded->location->geo->longitude))? $decoded->location->geo->longitude: "?";

        print $eventName."<br/>";
        print $eventDate."<br/>";
        print $eventURL."<br/>";
        print $venueName."<br/>";
        print $address_1."<br/>";
        print $city."<br/>";
        print $state."<br/>";
        print $zip."<br/>";
        print $country."<br/>";
        print $lat."<br/>";
        print $lon."<br/>";
    
    } else {
        print "Event file does not exist.";
    }
   

?>
 
    </div>
</body>
</html>