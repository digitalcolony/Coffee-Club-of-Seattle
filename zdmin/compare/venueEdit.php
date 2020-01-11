<?php 
    $rootFolder = $_SERVER['DOCUMENT_ROOT'];
    $configFile = $rootFolder . "/src/php/config.php";
    $configs = include_once($configFile);
    include_once($rootFolder . "/src/php/connect.php");

    if(isset($_POST['submit'])){
        // update DB
        $thisVenueID = $_POST['venueID'];
        $thisVenueName = mysqli_real_escape_string ($conn, $_POST['venueName']);
        $thisAddress_1 = mysqli_real_escape_string($conn,$_POST['address_1']);
        $thisAddress_2 = mysqli_real_escape_string($conn,$_POST['address_2']);
        $thisCity = mysqli_real_escape_string($conn,$_POST['city']);
        $thisState = substr($_POST['state'],0,2);;
        $thisZip = $_POST['zip'];
        $thisCountry = substr($_POST['country'],0,2);
        $thisLat = $_POST['lat'];
        $thisLon = $_POST['lon'];
        $thisVenueType = $_POST['venueType'];
        $thisVenueStatus = $_POST['venueStatus'];   

        $updateVenueSQL = "CALL spUpdateCCVenue (".$thisVenueID.",
        '".$thisVenueName."',
        '".$thisAddress_1."',
        '".$thisAddress_2."',
        '".$thisCity."',
        '".$thisState."',
        '".$thisZip."',
        '".$thisCountry."',
        ".$thisLat.",
        ".$thisLon.",
        '".$thisVenueType."',
        '".$thisVenueStatus."'
        )";

        // echo $updateVenueSQL;
        // exit();
        mysqli_query($conn, $updateVenueSQL) or die("Query fail: " . mysqli_error());
        header('Location: ./venues.php');
    }
    elseif(isset($_GET['id']))
    {
        $thisVenueID = $_GET['id'];
        // confirm ID is numeric
        if(is_numeric($thisVenueID)){
            $venueGetSQL = "SELECT venueName, address_1, address_2, city, state, zip, country, lat, lon, venueType, venueStatus
                FROM cc_venues WHERE cc_venueID = ".$thisVenueID;

            mysqli_query($conn, $venueGetSQL) or die("Query fail: " . mysqli_error());
            $result = $conn->query($venueGetSQL);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $thisVenueName = $row["venueName"];
                $thisAddress_1 = $row["address_1"];
                $thisAddress_2 = $row["address_2"];
                $thisCity = $row["city"];
                $thisState = $row["state"];
                $thisZip = $row["zip"];
                $thisCountry = $row["country"];
                $thisLat = $row["lat"];
                $thisLon = $row["lon"];
                $thisVenueType = $row["venueType"];
                $thisVenueStatus = $row["venueStatus"];
            } else {
                echo "Could not find Venue. Return to <a href='./venues.php'>Venue List</a>";
                exit();
            }

        } else {
            header('Location: ./venues.php');
        }

       
    } else {
        header('Location: ./venues.php');
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
      <link href="/src/css/admin.css?v=2" rel="stylesheet">
    <title>Coffee Club Admin - Venue Edit</title>
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
        <li class='nav-item'><a class='text-info nav-link' href='/zdmin/venues.php'>Venues</a></li>
        </ul>
    </div>
</nav>
<div class="pageSection">
    <h3>Edit Venue</h3>


<form action="venueEdit.php" name="venueEdit" id="venueEdit" role="form" method="post">
<input type="hidden" id="venueID" name="venueID" value="<?php echo($thisVenueID) ?>">
  <div class="form-group row">
    <label for="venueName" class="col-sm-2 col-form-label">Venue Name:</label>
    <div class="col-sm-6">
      <input type="text" class="form-control" name="venueName" id="venueName" value="<?php echo($thisVenueName) ?>">
    </div>
  </div>
  <div class="form-group row">
    <label for="address_1" class="col-sm-2 col-form-label">Address:</label>
    <div class="col-sm-6">
      <input type="text" class="form-control" name="address_1" id="address_1" value="<?php echo($thisAddress_1) ?>">
      <br/>
      <input type="text" class="form-control" name="address_2" id="address_2" value="<?php echo($thisAddress_2) ?>">
    </div>
  </div>
  <div class="form-group row">
    <label for="city" class="col-sm-2 col-form-label">City:</label>
    <div class="col-sm-6">
      <input type="text" class="form-control" name="city" id="city" value="<?php echo($thisCity) ?>">
    </div>
  </div>
  <div class="form-group row">
    <label for="state" class="col-sm-2 col-form-label">State:</label>
    <div class="col-sm-1">
      <input type="text" class="form-control" name="state" id="state" value="<?php echo($thisState) ?>">
    </div>
  </div>
  <div class="form-group row">
    <label for="zip" class="col-sm-2 col-form-label">Zip:</label>
    <div class="col-sm-2">
      <input type="text" class="form-control" name="zip" id="zip" value="<?php echo($thisZip) ?>">
    </div>
  </div>
  <div class="form-group row">
    <label for="country" class="col-sm-2 col-form-label">Country:</label>
    <div class="col-sm-1">
      <input type="text" class="form-control" name="country" id="country" value="<?php echo($thisCountry) ?>">
    </div>
  </div>
  <div class="form-group row">
    <label for="lat" class="col-sm-2 col-form-label">Latitude:</label>
    <div class="col-sm-3">
      <input type="text" class="form-control" name="lat" id="lat" value="<?php echo($thisLat) ?>">
    </div>
  </div>
  <div class="form-group row">
    <label for="lon" class="col-sm-2 col-form-label">Longitude:</label>
    <div class="col-sm-3">
      <input type="text" class="form-control" name="lon" id="lon" value="<?php echo($thisLon) ?>">
    </div>
  </div>

  <fieldset class="form-group">
    <div class="row">
      <legend class="col-form-label col-sm-2 pt-0">Venue Type</legend>
      <div class="col-sm-10">
        <div class="form-check">
          <input class="form-check-input" type="radio" name="venueType" id="venueType1" 
          value="Standard" <?php if($thisVenueType=='Standard') echo "checked"; ?>>
          <label class="form-check-label" for="venueType1">
            Coffee (Standard)
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="venueType" id="venueType2" 
          value="Other" <?php if($thisVenueType=='Other') echo "checked"; ?>>
          <label class="form-check-label" for="venueType2">
            Other
          </label>
        </div>
      </div>
    </div>
  </fieldset>

  <fieldset class="form-group">
    <div class="row">
      <legend class="col-form-label col-sm-2 pt-0">Venue Status</legend>
      <div class="col-sm-10">
        <div class="form-check">
          <input class="form-check-input" type="radio" name="venueStatus" id="venueStatus1" 
            value="Active" <?php if($thisVenueStatus=='Active') echo "checked"; ?>>
          <label class="form-check-label" for="venueStatus1">
            Active 
          </label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="venueStatus" id="venueStatus2" 
            value="Inactive" <?php if($thisVenueStatus=='Inactive') echo "checked"; ?>>
          <label class="form-check-label" for="venueStatus2">
            Inactive
          </label>
        </div>
      </div>
    </div>
  </fieldset>

  <div class="form-group row">
    <label for="lon" class="col-sm-2 col-form-label"></label>
    <div class="col-sm-3">
    <input type="submit" name="submit" value="Update Venue"  class="btn btn-primary mt-3 mb-5" />
    </div>
  </div>

  
</form>
</div>
</body>
</html>