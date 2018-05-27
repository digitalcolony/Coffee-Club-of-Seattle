<?php 
    $configs = include("config.php");
    date_default_timezone_set('America/Los_Angeles');
?>
<!DOCTYPE html>
<html>
  <head>
        <title><?php echo($configs->GROUP_NAME); ?> | New Cafe Report</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta property="og:image" content="<?php echo($configs->OG_IMAGE_URL); ?>" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo($configs->GROUP_NAME); ?> New Cafe Report" />
		<meta property="og:description" content="<?php echo($configs->GROUP_NAME); ?> New Cafe Report" />
		<meta property="og:url" content="<?php echo($configs->PAGE_URL); ?>" />
		<meta property="og:site_name" content="<?php echo($configs->GROUP_NAME); ?>" />
		<meta property="fb:app_id" content="<?php echo($configs->FACEBOOK_APP_ID); ?>" />
        <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
	    <link rel="stylesheet" type="text/css" href="i/coffee.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <body>
    <p><a href=".">Venue Report</a></p>
   
    <h3>Yelp New Coffee Report</h3> 
    <p>These businesses have labeled themselves with YELP as "Coffee" in one of their categories.</p>
    <p>Yelp returns "new" businesses only after they have at least 1 valid 
    review.</p> 
    <p>So these are leads and not necessarily new coffee shops.</p>
<ol>
<?php
    $json_file = "./i/newcafes.json";
    $json = file_get_contents($json_file);
    $jsonFileDateString = "This report was last ran on " . date ("F d, Y", filemtime($json_file)).".";
    
    $decoded = json_decode($json);
    $cafes = $decoded->businesses;

    foreach ($cafes as $cafe) {
        $categories = $cafe->categories;
        
       
            echo "<li><a href='".$cafe->url."' target='_new'>".$cafe->name."</a> ";
            echo $cafe->location->address1 .", ".$cafe->location->city;
            echo " (";
            $catString ="";
            foreach($categories as $category){
                $catString = $catString.$category->alias." - ";
            }
            echo substr($catString,0,-3);
            echo ")</li>";
        
    }
?>
</ol>
<p><?php echo($jsonFileDateString) ?></p>
 </body>
</html>