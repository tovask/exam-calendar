<?php
require "before_content.php";
?>
<?php
if(!isset($_GET['q'])){
	$_GET['q'] = '';
}
print "                <form class='' action='search.php' method='GET' >\n";
print "                    <input type='text' name='q' value='".htmlspecialchars($_GET['q'],ENT_QUOTES)."' class='form-control' placeholder='Keresés...' />\n";
//print "                    <input type='submit' value='Keres' class='btn btn-success' />\n";
print "                </form>\n";
$results = execquery("SELECT `events`.`id` AS 'event_id', `events`.`time` AS 'time', `events`.`title` AS 'title', `places`.`id` AS 'place_id', `places`.`building` AS 'building' FROM `events` JOIN `places` ON `events`.`place_id`=`places`.`id` WHERE `events`.`title` LIKE '%".$mysqli->real_escape_string($_GET['q'])."%' OR `events`.`description` LIKE '%".$mysqli->real_escape_string($_GET['q'])."%' ORDER BY `events`.`time` ");
if(count($results)<1){
	print "<span>Nincs a feltételeknek megfelelő esemény.</span>";
}else{
	print "                <table class='table table-striped text-center'>\n";
	print "                    <thead>\n";
	print "                        <tr>\n";
	print "                            <th>Időpont</th>\n";
	print "                            <th>Cím</th>\n";
	print "                            <th>Helyszín</th>\n";
	print "                        </tr>\n";
	print "                    </thead>\n";
	print "                    <tbody>\n";
	foreach($results as $event){
		print "                        <tr>";
		print '<td>'.date('Y.m.d H:i',strtotime($event['time']))."</td>";
		print '<td><a href="event.php?id='.$event['event_id'].'">'.htmlspecialchars($event['title'],ENT_QUOTES).'</a></td>';
		print '<td><a href="place.php?id='.$event['place_id'].'">'.htmlspecialchars($event['building'],ENT_QUOTES).'</a></td>';
		print "</tr>\n";
	}
	print "                    </tbody>\n";
	print "                </table>\n";
}
?>
<?php
require "after_content.php";
?>