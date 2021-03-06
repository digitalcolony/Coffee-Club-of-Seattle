<?php 
    $configs = include("./src/php/config.php");
    date_default_timezone_set($configs->CRON_TIMEZONE);
    header("Cache-Control: max-age=14400"); //4 hours (60sec * 60min * 4)
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title><?php echo($configs->GROUP_NAME); ?> | Leads Report</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta property="og:image" content="<?php echo($configs->OG_IMAGE_URL); ?>" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo($configs->GROUP_NAME); ?> Leads Report" />
		<meta property="og:description" content="<?php echo($configs->GROUP_NAME); ?> Leads Report" />
		<meta property="og:url" content="<?php echo($configs->PAGE_URL); ?>" />
		<meta property="og:site_name" content="<?php echo($configs->GROUP_NAME); ?>" />
		<meta property="fb:app_id" content="<?php echo($configs->FACEBOOK_APP_ID); ?>" />
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
    <link rel="stylesheet" type="text/css" href="/src/css/coffee.css?v=<?php echo($configs->CSS_VERSION); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="/src/js/jquery-3.3.1.min.js"></script>	
    <link href="/src/css/bootstrap.min.css" rel="stylesheet">
    <script src="/src/js/bootstrap.min.js"></script>	    
</head>
<body>
<?php
	// insert nav menu
	$currentPage = "Leads";
	include("./src/php/menu.php");
?>
<div class="container-fluid" style="padding-top:80px">
    <p>These new businesses have labeled themselves with YELP as "Coffee" in one of their 
    categories. They are leads and not necessarily coffee shops.</p>
<ol>
<?php
    $json_file = "./src/data/newcafes.json";
    $json = file_get_contents($json_file);
    $jsonFileDateString = "This report last ran on <strong>" . date ("F d, Y", filemtime($json_file))."</strong>.";
    $decoded = json_decode($json);
    $cafes = $decoded->businesses;

    foreach ($cafes as $cafe) {
        $categories = $cafe->categories;
        echo "<li><a href='".$cafe->url."' target='_new'>".$cafe->name."</a> ";
        echo $cafe->location->address1 .", <strong>".$cafe->location->city."</strong>"  ;
        echo " (";
        $catString ="";
        foreach($categories as $category){
            $catString = $catString.$category->title." + ";
        }
        echo substr($catString,0,-3);
        echo ")</li>";
    }
?>
  </ol>
  <p>Yelp returns "new" businesses only after they have at least 1 valid review.</p> 
  <p><?php echo($jsonFileDateString) ?></p>
  </div>
<?php 
	include_once("./src/php/google.php");
?>
</body>
</html>