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
    <title>Coffee Club Admin - Venue List</title>
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
        <li class='nav-item'><span class='text-warning nav-link'>Venues</span></li>
        </ul>
    </div>
</nav>
<div class="pageSection">
    <table class="table">
    <thead>
    <tr class="table-success">
        <th scope="col">#</th>
        <th scope="col">Venue</th>
        <th scope="col">Type</th>
        <th scope="col">Status</th>
        <th scope="col">Action</th>
    </tr>
    </thead>
    <tbody>
<?php 
    $venueListSQL = "SELECT cc_venueID, venueName, venueType, venueStatus FROM cc_venues ORDER BY venueName";
    $result = mysqli_query($conn, $venueListSQL) or die("Query fail: " . mysqli_error());
    $j = 0;
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $j = $j+1;
            if($row["venueType"] == 'Other' || $row["venueStatus"] == 'Inactive'){
                echo "<tr class='table-secondary'>";
            } else {
                echo "<tr>";
            }
            
            echo "<th scope='row'>".$j."</th>";
            echo "<td>".$row["venueName"]."</td>";
            echo "<td>".$row["venueType"]."</td>";
            echo "<td>".$row["venueStatus"]."</td>";
            echo "<td><a href='venueEdit.php?id=".$row{"cc_venueID"}."'  class='badge badge-warning'>Edit</a>";
            echo "  <a href='eventList.php?vid=".$row{"cc_venueID"}."'  class='badge badge-info'>Events</a>";
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
</html>