<?php
require "before_content.php";
?>
<?php
$attributes = ['building','room','GPS_lat','GPS_lon'];
if(isset($_GET['id'])){	// show one
	if($_GET['id']==='0'){	// insert new
		$values = [];
		foreach($attributes as $attribute){
			if(isset($_POST[$attribute])){
				$values[] = $mysqli->real_escape_string($_POST[$attribute]);
			}else{
				$values[] = "";
			}
		}
		execquery("INSERT INTO `places` (`".implode('`,`',$attributes)."`) VALUES ('".implode("','",$values)."') ");
		$_GET['id'] = db_last_inserted_id();
	}else{	// modify
		foreach($attributes as $attribute){
			if(isset($_POST[$attribute])){
				execquery("UPDATE `places` SET `".$attribute."` = '".$mysqli->real_escape_string($_POST[$attribute])."' WHERE `id`=".intval($_GET['id'])." ");
			}
		}
	}
	$place = execquery("SELECT * FROM `places` WHERE `id` = ".intval($_GET['id'])." ");
	if(count($place)===1){
		$place = $place[0];
		$can_delete = count(execquery("SELECT * FROM `events` WHERE `place_id` = ".$place['id']." ")) === 0;
		print "                <a href='place.php?edit=".$place['id']."' class='btn btn-warning' >Szerkesztés</a>\n";
		print "                <a href='place.php?delete=".$place['id']."' class='btn btn-danger".($can_delete?"":" disabled")."' ".($can_delete?"":"title='Nem törölheted, amíg hivatkozik rá esemény!' ").">Törlés</a>\n";
		if(!$can_delete){
			print "                (Nem lehet törölni, amíg hivatkozik rá esemény!)\n";
		}
		print "                <h2>".htmlspecialchars($place['building'],ENT_QUOTES)."</h2>\n";
		print "                <h2>".htmlspecialchars($place['room'],ENT_QUOTES)."</h2>\n";
		print "                <h4>GPS: ".htmlspecialchars($place['GPS_lat'],ENT_QUOTES).", ".htmlspecialchars($place['GPS_lon'],ENT_QUOTES)."</h4>\n";
		if($_GET['id']!=='0'){
			//print "<iframe width='425' height='350' src='//maps.google.com/maps?q=London&amp;ie=UTF8&amp;&amp;output=embed' ></iframe><br />";
			print "                <br><iframe width='500' height='450' src='//maps.google.com/maps?q=".htmlspecialchars($place['GPS_lat'],ENT_QUOTES).", ".htmlspecialchars($place['GPS_lon'],ENT_QUOTES)."&amp;ie=UTF8&amp;&amp;output=embed' ></iframe>\n";
		}
	}else{
		print "                <span>A keresett helyszín nem található.</span>\n";
	}
}elseif(isset($_GET['edit'])){	// edit or create
	if($_GET['edit']==='0'){	// new one
		print "                <h1>Új helyszín</h1>\n";
		$place = [ [ 'id'=>0 , 'building'=>'','room'=>'','GPS_lat'=>'','GPS_lon'=>''] ];
	}else{
		$place = execquery("SELECT * FROM `places` WHERE `id` = ".intval($_GET['edit'])." ");
	}
	if(count($place)===1){
		$place = $place[0];
		print "                <form action='place.php?id=".$place['id']."' method='POST' >\n";
		print "                    Épület: <input type='text' name='building' value='".htmlspecialchars($place['building'],ENT_QUOTES)."' class='form-control' /><br>\n";
		print "                    Terem: <input type='text' name='room' value='".htmlspecialchars($place['room'],ENT_QUOTES)."' class='form-control' /><br>\n";
		print "                    GPS_lat: <input type='text' name='GPS_lat' value='".htmlspecialchars($place['GPS_lat'],ENT_QUOTES)."' class='form-control' /><br>\n";
		print "                    GPS_lon: <input type='text' name='GPS_lon' value='".htmlspecialchars($place['GPS_lon'],ENT_QUOTES)."' class='form-control' /><br>\n";
		print "                    <input type='submit' value='Mentés' class='btn btn-success' />\n";
		print "                </form>\n";
	}else{
		print "                <span>A szerkesztendő helyszín nem található.</span>\n";
	}
}elseif(isset($_GET['delete'])){	// delete
	$place = execquery("SELECT * FROM `places` WHERE `id` = ".intval($_GET['delete'])." ");
	if(count($place)===1){
		$place = $place[0];
		$can_delete = count(execquery("SELECT * FROM `events` WHERE `place_id` = ".$place['id']." ")) === 0;
		if($can_delete){
			execquery("DELETE FROM `places` WHERE `id` = ".$place['id']." ");
		}
		redirect("./place.php");
	}else{
		print "                <span>A keresett helyszín nem található.</span>\n";
	}
}else{	// list all
	$all = execquery("SELECT * FROM `places` ");
	if(count($all)<1){
		print "<span>Nincs még egy helyszín sem.</span>";
	}else{
		print "                <table class='table table-striped text-center'>\n";
		print "                    <thead>\n";
		print "                        <tr>\n";
		print "                            <th>Épület</th>\n";
		print "                            <th>Terem</th>\n";
		print "                            <th>GPS</th>\n";
		print "                        </tr>\n";
		print "                    </thead>\n";
		print "                    <tbody>\n";
		foreach($all as $place){
			print "                        <tr>";
			print '<td><a href="place.php?id='.$place['id'].'">'.htmlspecialchars($place['building'],ENT_QUOTES).'</a></td>';
			print '<td><a href="place.php?id='.$place['id'].'">'.htmlspecialchars($place['room'],ENT_QUOTES).'</a></td>';
			print '<td><a href="place.php?id='.$place['id'].'">'.htmlspecialchars($place['GPS_lat'],ENT_QUOTES)." ".htmlspecialchars($place['GPS_lon'],ENT_QUOTES).'</a></td>';
			print "</tr>\n";
		}
		print "                    </tbody>\n";
		print "                </table>\n";
	}
}
?>
<?php
require "after_content.php";
?>