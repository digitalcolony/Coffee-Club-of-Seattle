<?php 
    $rootFolder = $_SERVER['DOCUMENT_ROOT'];
    $configFile = $rootFolder . "/src/php/config.php";
    $configs = include_once($configFile);
    include_once($rootFolder . "/src/php/connect.php");
    
    if(isset($_POST['submit']))
    {
        $thisEventID = $_POST['eventID'];
        $thisEventLink = $_POST['eventLink'];
        $thisEventName = mysqli_real_escape_string($conn, $_POST['eventName']);
        $thisEventDate = $_POST['eventDate'];
        $thisVenueID = $_POST['venueID'];
        $thisVenueName = mysqli_real_escape_string ($conn, $_POST['venueName']);
        $thisAddress_1 = mysqli_real_escape_string($conn,$_POST['address_1']);
        $thisCity = mysqli_real_escape_string($conn,$_POST['city']);
        $thisState = $_POST['state'];
        $thisZip = $_POST['zip'];
        $thisCountry = substr($_POST['country'],0,2);
        $thisLat = $_POST['lat'];
        $thisLon = $_POST['lon'];   

        $sql = "CALL spAddCCEvent (".$thisEventID.",
                    '".$thisEventLink."',
                    '".$thisEventName."',
                    '".$thisEventDate."',
                    ".$thisVenueID.",
                    '".$thisVenueName."',
                    '".$thisAddress_1."',
                    '".$thisCity."',
                    '".$thisState."',
                    '".$thisZip."',
                    '".$thisCountry."',
                    ".$thisLat.",
                    ".$thisLon.")";

        mysqli_query($conn, $sql) or die("Query fail: " . mysqli_error());

        $json_file = $rootFolder . "/src/data/events/" . $thisEventID . ".json";
        if(file_exists($json_file)){
            unlink($json_file);
        }
        header('Location: ./');
    }

    if(isset($_POST['delete'])){
        $thisEventID = $_POST['eventID'];
        $json_file = $rootFolder . "/src/data/events/" . $thisEventID . ".json";
        if(file_exists($json_file)){
            unlink($json_file);
        }
        header('Location: ./');
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
    <title>Coffee Club Admin - Process Events</title>
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
        <li class='nav-item'><a class='text-info nav-link' href='/zdmin/venues.php'>Venues</a></li>
        </ul>
    </div>
</nav>

<div class="container-fluid pageSection">
  <h3>Process </h3>
 

<?php
    // parse JSON file
    if (isset($_GET['json'])){
        $eventID = $_GET['json'];
        $eventPageURL = "https://www.meetup.com/seattle-coffee-club/events/";
        $json_file = $rootFolder . "/src/data/events/" . $eventID . ".json";
        if(file_exists($json_file)){
            $json = file_get_contents($json_file);
            $jsonFileDateString = "This event was uploaded on <strong>" . date ("F d, Y", filemtime($json_file))."</strong>.";
            $decoded = json_decode($json);
    
            $eventName = (isset($decoded->name)) ? $decoded->name : "?";
            $eventDateTemp = (isset($decoded->startDate)) ? $decoded->startDate : "2000-12-31T12:00-07:00";
            // clean up date
            $eventDateTemp = substr($eventDateTemp,0,16);
            $eventDate = new DateTime($eventDateTemp);
    
            $eventURL = (isset($decoded->url)) ? $decoded->url : $eventPageURL. $eventID ."/";
    
            $venueName = (isset($decoded->location->name))? $decoded->location->name: "?";
            $address_1 = (isset($decoded->location->address->streetAddress))? $decoded->location->address->streetAddress: "?";
            $city = (isset($decoded->location->address->addressLocality))? $decoded->location->address->addressLocality: "?";
            $state = (isset($decoded->location->address->addressRegion))? $decoded->location->address->addressRegion: "?";
            $zip = (isset($decoded->location->address->postalCode))? $decoded->location->address->postalCode: "?";
            $country = (isset($decoded->location->address->addressCountry))? $decoded->location->address->addressCountry: "?";
            $lat = (isset($decoded->location->geo->latitude))? $decoded->location->geo->latitude: "0";
            $lon = (isset($decoded->location->geo->longitude))? $decoded->location->geo->longitude: "0";
            // Check to see if Event has already been added (snuck in somehow)

            $result=mysqli_query($conn, "SELECT COUNT(*) AS EventCount FROM cc_events WHERE eventID =".$eventID);
            $data=mysqli_fetch_assoc($result);

        } else {
           echo("<p>Event file does not exist. Return to Queue.</p>");
           $lat = 0;
           $lon = 0;
        }
    } else {
        echo("<p>Event file does not exist. Return to Queue.</p>");
        $lat = 0;
        $lon = 0;
    }

?>

    <div class="container">
    <form id="process" name="process" action="process.php" class="form-horizontal" role="form" method="post">
    <input type="hidden" id="eventID" name="eventID" value="<?php echo($eventID) ?>">
    <input type="hidden" id="eventLink" name="eventLink" value="<?php echo($eventURL) ?>">
        
    <input type="hidden" id="eventDate" name="eventDate" value="<?php echo($eventDate->format('Y/m/d H:i:s')) ?>"> 
        <input type="hidden" id="venueName" name="venueName" value="<?php echo($venueName) ?>">
        <input type="hidden" id="address_1" name="address_1" value="<?php echo($address_1) ?>">
       
        <input type="hidden" id="city" name="city" value="<?php echo($city) ?>">
        <input type="hidden" id="state" name="state" value="<?php echo($state) ?>">
        <input type="hidden" id="zip" name="zip" value="<?php echo($zip) ?>">
        <input type="hidden" id="country" name="country" value="<?php echo($country) ?>">
        <input type="hidden" id="lat" name="lat" value="<?php echo($lat) ?>">
        <input type="hidden" id="lon" name="lon" value="<?php echo($lon) ?>">

        <p><a href="<?php echo($eventURL) ?>" target="_blank"><?php echo($eventURL) ?></a>
        <div class="row form-group">
            <div class="col-8">
                <label for="eventName" class="control-label">Event Name:</label> 
                <input type="text" class="form-control" name="eventName" id="eventName" placeholder="Event Name" value="<?php echo($eventName) ?>">
                <p class="lead"><?php echo($eventDate->format('D M d Y')) ?></p>
            </div>
        </div>
     
        <!-- Existing or New venue? -->
        <div class="row form-group">
            <div class="col-8">
                <label for="venueID" class="control-label">Venue:</label>
            <select name="venueID" id="venueID" class="custom-select custom-select-m">
                <option value="0">-- New --</option>
<?php
       $query = "CALL spGetCCVenues (".$lat .",". $lon .")";
       //run the store proc
       $result = mysqli_query($conn, $query) or die("Query fail: " . mysqli_error());
       if ($result->num_rows > 0) {
           // output data of each row
           while($row = $result->fetch_assoc()) {
            echo "<option value=". $row["cc_venueID"];
               if($row["cc_venueID"] == $row["cc_venueID_init"]){
                     echo " selected";
               }
               if($row["venueType"]=="Other" || $row["venueType"]=="Inactive"){
                   echo " class='font-italic small'";
               }
               
               echo ">".$row["venueName"]."</option>";
           }
       }
       $conn->close();
?>                
            </select>
            </div>
        </div>

        <div class="row">
            <div class="col-8">
                <h5><?php echo($venueName) ?></h5>
                <address>
                <?php echo($address_1) ?><br/>
                <?php echo($city) ?>, <?php echo($state) ?>  <?php echo($country) ?><br/>
                <?php echo($lat) ?>, <?php echo($lon) ?>
                </address>
            </div>
        </div>

        <div class="row form-group">
            <div class="col-sm-8">
            <?php 
                if($data['EventCount'] > 0) {
            ?>
                <p>Event already exists. Delete JSON file.</p>
                <input type="submit" name="delete" Value="Delete" class="btn btn-danger mt-3 mb-5"/>
            <?php
                } else {
            ?>    
                 <input type="submit" name="submit" value="Submit"  class="btn btn-primary mt-3 mb-5" />
                    
            <?php
                }
            ?>
             </div>
        </div>
    </form>
    </div>
</div>

</body>
</html>