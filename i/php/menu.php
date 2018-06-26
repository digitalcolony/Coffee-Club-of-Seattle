
<nav id="topNav" class="navbar navbar-expand-md bg-dark navbar-dark fixed-top">
    <a class="navbar-brand text-white" href="/"><?php echo($configs->GROUP_NAME); ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
	    <ul class="navbar-nav">
<?php
    $pages = array(
        array("Venues","/"),
        array("Map", "map.php"),
        array("Stats", "stats.php"),
        array("Leads", "leads.php"),
        array("Meetup","https://www.meetup.com/".$configs->GROUP_URLNAME."/"),
        array("GitHub","https://github.com/digitalcolony/Coffee-Club-of-Seattle")  
    );

    for($row=0; $row<sizeof($pages);$row++) {
        $thisPage = $pages[$row][0];
        $thisURL = $pages[$row][1];
            echo "<li class='nav-item'>";
            if($thisPage==$currentPage){
                echo "<span class='text-warning nav-link'>".$thisPage."</span>";
            } else {
                // If link is external, open in new tab
                if(substr($thisURL,0,4)=="http"){
                    $thisTargetString = " target='_blank'"; 
                } else {
                    $thisTargetString = "";
                }
                echo "<a class='nav-link text-info'".$thisTargetString." href='".$thisURL."'>".$thisPage."</a>";
            }
            echo "</li>";
    }
?>
        </ul>			
    </div>
</nav>