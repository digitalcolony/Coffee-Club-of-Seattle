<?php 
    $configs = include("config.php");
    date_default_timezone_set('America/Los_Angeles');
?>
<!DOCTYPE html>
<html>
  <head>
        <title><?php echo($configs->GROUP_NAME); ?> | Leads Report</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta property="og:image" content="<?php echo($configs->OG_IMAGE_URL); ?>" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="<?php echo($configs->GROUP_NAME); ?> Leads Report" />
		<meta property="og:description" content="<?php echo($configs->GROUP_NAME); ?> Leads Report" />
		<meta property="og:url" content="<?php echo($configs->PAGE_URL); ?>" />
		<meta property="og:site_name" content="<?php echo($configs->GROUP_NAME); ?>" />
		<meta property="fb:app_id" content="<?php echo($configs->FACEBOOK_APP_ID); ?>" />
        <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
	    <link rel="stylesheet" type="text/css" href="i/coffee.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script type="text/javascript" src="i/jquery-latest.js"></script>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
  	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
 
   </head>
  <body>
  <nav id="topNav" class="navbar navbar-expand-md bg-dark navbar-dark fixed-top">
	 	<a class="navbar-brand text-white" href="/"><?php echo($configs->GROUP_NAME); ?></a>

		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
   		 <span class="navbar-toggler-icon"></span>
  	</button>

	<div class="collapse navbar-collapse" id="collapsibleNavbar">
	 <ul class="navbar-nav">
	 <li class="nav-item">
      <a class="nav-link text-info" href="/">Venues</a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-info" href="map.php">Map</a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-info" href="stats.php">Stats</a>
    </li>
    <li class="nav-item">
      
      <span class="text-warning nav-link">Leads</span>
    </li>
		<li class="nav-item">
      <a class="nav-link text-info" target="_blank" href="https://www.meetup.com/<?php echo($configs->GROUP_URLNAME); ?>/">Meetup</a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-info" target="_blank" href="https://github.com/digitalcolony/Coffee-Club-of-Seattle">GitHub</a>
    </li>
  </ul>			
	</div>
</nav>
<div class="container-fluid" style="padding-top:80px">
    <p>These new businesses have labeled themselves with YELP as "Coffee" in one of their 
    categories. They are leads and not necessarily coffee shops.</p>
<ol>
<?php
    $json_file = "./i/newcafes.json";
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
 </body>
</html>