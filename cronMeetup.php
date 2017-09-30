<?php
    // This page will be executed by a cron job once a day. 
    // It will check Meetup for newly completed Meetup Events.
    // The Event will be added to our Database for reporting. 
    // If the Venue is new, that will be added as well. 
    // Some Venues have duplicate entries on Meetup. We use a venuesmapping table to avoid duplicate entries. 
    // Whenever a new Venue is entered, an email to confirm it is truely new. 
    // If a new venue isn't really new but a duplicate, the data will be handled by hand (for now). 

    //MySQL Database connection 
    $configs = include("config.php");
    include "connect.php";
    require 'meetup.php';
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Nightly Meetup Job </title>
    <meta name="robots" content="noindex, nofollow">
  </head>
  <body>
<?php

$meetup = new Meetup(array(
    'key' => $configs->API_KEY
));

// Meetup will return 200 records at a time. 
// When you first populate your database, you'll need to tweak this code. 
// Once our database was populated, we had 1,000+ Meetups, so our initial offset is 5. 
// That way we aren't querying our first 1,000 Meetups every night. 
// ISSUE: We need to upset offset every 200 Meetups
// TODO: Clean up Offset start and end code to know which Meetup # the group is up to. 
$offset = 5; 

for ($j=$offset; $j<=$offset+1 ;$j++)
{
    $response = $meetup->getEvents(array(
        'group_urlname' => $configs->GROUP_URLNAME,
        'status' => 'past',
        'omit' => 'description',
        'desc' => 'false',  
        'offset' => $j
    ));

    foreach ($response->results as $event) 
        {
            $thisEventID = $event->id;    
            $sql0 = 'SELECT eventID AS EventID FROM events WHERE eventID ='.$thisEventID;
            $result = $conn->query($sql0);           
            if ($result->num_rows == 0) {                           
                $thisVenueID = $event->venue->id;
                $thisEventLink = $event->event_url;
                $thisEPOCH = $event->time / 1000;
                $thisGMT = gmdate('r', $thisEPOCH);
                $TimeZoneNameFrom="UTC";
                $TimeZoneNameTo=$configs->CRON_TIMEZONE;
                $thisEventDate =  date_create($thisGMT, new DateTimeZone($TimeZoneNameFrom))
                     ->setTimezone(new DateTimeZone($TimeZoneNameTo))->format("Y-m-d H:i:s");
                $thisEventName = mysql_escape_string($event->name);

                echo "<p>Adding: ".$thisEventName."</p>";

                // #1 Insert to events 
                $sql = "INSERT INTO events (
                        eventID, 
                        eventLink, 
                        eventName, 
                        eventDate, 
                        venueID)
                    VALUES (".$thisEventID.",'".$thisEventLink."','".$thisEventName."','".$thisEventDate."',".$thisVenueID.")";
                
                if ($conn->query($sql) === TRUE) {
			        echo "<p>Event Added: ".$thisEventName."  @".$thisEventDate. "</p>";
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }              
                // #2 Query VenueMapped                                                                  
                $sql2 = "SELECT venueIDmapped FROM venuesmapped WHERE venueID = ".$thisVenueID;
                $result = $conn->query($sql2);
	            if ($result->num_rows > 0) {
                    // An exisiting venue was found. Take the venueIDmapped as the venueID going forward. 
                    $row = $result->fetch_assoc();
                    $thisVenueID = $row["venueIDmapped"];
                    //echo "<p>Venue Found!</p>";
                } else{
                    echo "<p>Venue NOT Found. Adding.</p>";
                    // This is a new venue. Add row to venuemapped. Assume all is well with data.
                    $sql3 = "INSERT INTO venuesmapped (venueID, venueIDMapped) VALUES (".$thisVenueID.", ".$thisVenueID.")";
                    $conn->query($sql3);

                    $thisLat = $event->venue->lat;
                    $thisLon = $event->venue->lon;
                    $thisCity = mysql_escape_string($event->venue->city);
                    $thisVenueName = mysql_escape_string($event->venue->name);

                    if (isset($event->venue->zip)) { 
                        $thisZip = $event->venue->zip;    
                    } else {
                        $thisZip = '';
                    }
                    if (isset($event->venue->state)) { 
                        $thisState = strtoupper($event->venue->state);    
                    } else {
                        $thisState = $configs->CRON_DEFAULT_STATE;
                    }
                    if (isset($event->venue->country)) { 
                        $thisCountry = strtoupper($event->venue->country);    
                    } else {
                        $thisCountry = $configs->CRON_DEFAULT_COUNTRY;
                    }                                                          
                    $thisAddress1 = $event->venue->address_1;

                    if (isset($event->venue->address_2)) { 
                        $thisAddress2 = $event->venue->address_2;    
                    } else {
                        $thisAddress2 = '';
                    }
                    
                    // Add venue to venues
                    $sql4 = "INSERT INTO venues (venueID, lat, lon, zip, city, venueName, state, 
                        country, address_1, address_2) 
                        VALUES (".$thisVenueID.",".$thisLat.",".$thisLon.",'".$thisZip."','".$thisCity."','".$thisVenueName."',
                            '".$thisState."','".$thisCountry."','".$thisAddress1."','".$thisAddress2."')";
                    $conn->query($sql4);

                    // Add venue to venuesclean with 2 more fields. 
                    // All venues are considered 'Standard'. If venue is a priate resident or non-standard, change to 'Other' later.
                    $sql5 = "INSERT INTO venuesclean (venueID, lat, lon, zip, city, VenueName, state, 
                            country, address_1, address_2, venueType, venueStatus)
                        SELECT venueID, lat, lon, zip, city, VenueName, state, country, address_1, address_2, 'Standard', 'Active'
                        FROM venues
                        WHERE venueID = ".$thisVenueID;
                    $conn->query($sql5);
                    
                    // I only want to receive an alert when a new venue is added.
                    $mailMessage = $thisVenueName." added as a new venue.";
                    mail($configs->CRON_EMAIL,"Meetup Venue Alert",$mailMessage);
                }
                
                // #3 Insert to eventsclean 
                $sql6 = "INSERT INTO eventsclean (
                        eventID, 
                        eventLink, 
                        eventName, 
                        eventDate, 
                        venueID)
                    VALUES (".$thisEventID.",'".$thisEventLink."','".$thisEventName."','".$thisEventDate."',".$thisVenueID.")";
                $conn->query($sql6);
            }
             
        }
}
$conn->close();
?>

    <p>The Cron job to Meetup has completed!</p>
    <p>Return to <a href="<?php echo($configs->PAGE_URL); ?>">report</a>.</p>
  </body>
</html>
