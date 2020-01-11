<?php 
    $rootFolder = $_SERVER['DOCUMENT_ROOT'];
    $configFile = $rootFolder . "/src/php/config.php";
    $configs = include_once($configFile);
    include_once($rootFolder . "/src/php/connect.php");

    if(isset($_GET['vid']))
    {
        $thisVenueID = $_GET['vid'];
        // confirm ID is numeric
        if(!is_numeric($thisVenueID)){
            header('Location: ./venues.php');
        }
    }
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
    <title>Coffee Club Admin - Event List</title>
</head>
<body>
<nav id="topNav" class="navbar navbar-expand-md bg-dark navbar-dark fixed-top">
    <a class="navbar-brand text-white" href="/"><?php echo($configs->GROUP_NAME); ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
	    <ul class="navbar-nav">
        <li class='nav-item'><a class='text-info nav-link' href='/zdmin/'>Queue</a></li>
        <li class='nav-item'><a class='text-warning nav-link' href='/zdmin/venues.php'>Venues</a></li>
        <li class='nav-item'><span class='text-warning nav-link'>Events</span></li>
        </ul>
    </div>
</nav>
<div class="pageSection">
    <table class="table">
    <thead>
    <tr class="table-success">
        <th scope="col">#</th>
        <th scope="col">Event</th>
        <th scope="col">Date</th>
        <th scope="col">Action</th>
    </tr>
    </thead>
    <tbody>
<?php 
    $eventListSQL = "SELECT eventName, eventLink, eventDate
        FROM cc_events
        WHERE cc_venueID = ".$thisVenueID." ORDER BY eventDate DESC";
    $result = mysqli_query($conn, $eventListSQL) or die("Query fail: " . mysqli_error());
    $j = 0;
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $j = $j+1;
            echo "<th scope='row'>".$j."</th>";
            echo "<td>".$row["eventName"]."</td>";
            echo "<td>".$row["eventDate"]."</td>";
            echo "<td><span class='badge badge-warning'>Edit</span>";
           echo "</td>";
            echo "</tr>";
        }
    }
    $conn->close();

?>
    </tbody>
    </table>
</div>
</body>