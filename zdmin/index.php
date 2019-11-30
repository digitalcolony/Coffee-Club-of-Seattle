<?php 
    $rootFolder = $_SERVER['DOCUMENT_ROOT'];
    $configFile = $rootFolder . "/src/php/config.php";
    $configs = include_once($configFile);
    include_once($rootFolder . "/src/php/connect.php");
 
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
    <link href="/src/css/admin.css" rel="stylesheet">
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
        <li class='nav-item'><span class='text-warning nav-link'>Queue</span></li>
        <li class='nav-item'><a class='text-info nav-link'' href='/zdmin/venues.php'>Venues</a></li>
        </ul>
    </div>
</nav>

<div class="container-fluid" style="padding-top:80px">
  <h3>Event Queue For Processing</h3>
 

<?php
    // pull list of JSON Event files
    $directory = $rootFolder . "/src/data/events/";
    $events = glob($directory . "*.json");
    $eventCount = count($events);
    if($eventCount > 0) {
        print "<ol>";
        foreach($events as $event){
            print "<li><a href='process.php?json=". pathinfo($event)["filename"] . "'>". pathinfo($event)["filename"] . "</a></li>";
        }
        print "</ol>";
    } else {
        print "<p>The Event Queue is empty.";
    }
    

?>
    </div>
</body>
</html>