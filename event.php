<?php
require "before_content.php";
?>
<?php
$attributes = ['title','time','description','place_id','style_id'];
if(isset($_GET['id'])){	// show one
	if($_GET['id']==='0'){	// insert new
		$values = [];
		foreach($attributes as $attribute){
			if(isset($_POST[$attribute]) && strlen($_POST[$attribute])>0){
				$values[] = $mysqli->real_escape_string($_POST[$attribute]);
			}else{
				//$values[] = "";
				print "<script>alert('Üres mező!');</script>";
				redirect("./event.php?edit=0");
			}
		}
		if(count($values)===count($attributes)){
			execquery("INSERT INTO `events` (`".implode('`,`',$attributes)."`) VALUES ('".implode("','",$values)."') ");
			$_GET['id'] = db_last_inserted_id();
		}
	}else{	// modify
		foreach($attributes as $attribute){
			if(isset($_POST[$attribute])){
				execquery("UPDATE `events` SET `".$attribute."` = '".$mysqli->real_escape_string($_POST[$attribute])."' WHERE `id`=".intval($_GET['id'])." ");
			}
		}
	}
	$event = execquery("SELECT `events`.`id` AS 'id', `events`.`time` AS 'time', `events`.`title` AS 'title', `events`.`description` AS 'description', `places`.`id` AS 'place_id', `places`.`building` AS 'building', `places`.`room` AS 'room', `styles`.`id` AS 'style_id', `styles`.`name` AS 'style_name', `styles`.`font_size` AS 'font_size', `styles`.`font_color` AS 'font_color', `styles`.`bg_color` AS 'bg_color' FROM `events` JOIN `places` ON `events`.`place_id`=`places`.`id` JOIN `styles` ON `events`.`style_id`=`styles`.`id` WHERE `events`.`id` = ".intval($_GET['id'])." ORDER BY `events`.`time` ");
	if(count($event)===1){
		$event = $event[0];
		print "                <a href='event.php?edit=".$event['id']."' class='btn btn-warning' >Szerkesztés</a>\n";
		print "                <a href='event.php?delete=".$event['id']."' class='btn btn-danger' >Törlés</a>\n";
		print "                <h2>".htmlspecialchars($event['title'],ENT_QUOTES)."</h2>\n";
		print "                <h2>".date('Y.m.d H:i',strtotime($event['time']))."</h2>\n";
		print "                <h3>".htmlspecialchars($event['description'],ENT_QUOTES)."</h3>\n";
		print "                <h4><a href='place.php?id=".$event['place_id']."' >".htmlspecialchars($event['building'],ENT_QUOTES).": ".htmlspecialchars($event['room'],ENT_QUOTES)."</a></h4>\n";
		print "                <h4>Típus: <a href='style.php?id=".$event['style_id']."' >".htmlspecialchars($event['style_name'],ENT_QUOTES)."</a></h4>\n";
		$alerts = execquery("SELECT * FROM `alerts` WHERE `event_id`=".$event['id']." ");
		foreach($alerts as $alert){
			print "                <h4>Figyelmeztetés: ".date('Y.m.d H:i',strtotime($alert['time']))."</h4>\n";
		}
	}else{
		print "                <span>A keresett esemény nem található.</span>\n";
	}
}elseif(isset($_GET['edit'])){	// edit or create
	if($_GET['edit']==='0'){	// new one
		print "                <h1>Új esemény</h1>\n";
		$event = [ [ 'id'=>0 , 'title'=>'','time'=>mysql_date(get_today_first_time()+32*60*60),'description'=>'','place_id'=>0,'style_id'=>0] ];
	}else{
		$event = execquery("SELECT * FROM `events` WHERE `id` = ".intval($_GET['edit'])." ");
	}
	if(count($event)===1){
		$event = $event[0];
		print "                <form action='event.php?id=".$event['id']."' method='POST' >\n";
		print "                    Cím: <input type='text' name='title' value='".htmlspecialchars($event['title'],ENT_QUOTES)."' class='form-control' /><br>\n";
		print "                    Dátum: <input type='text' name='time' value='".htmlspecialchars($event['time'],ENT_QUOTES)."' class='form-control' /><br>\n";
		print "                    Leírás: <input type='text' name='description' value='".htmlspecialchars($event['description'],ENT_QUOTES)."' class='form-control' /><br>\n";
		print "                    Helyszín: <select name='place_id' class='form-control' >\n";
		$places = execquery("SELECT * FROM `places` ");
		foreach($places as $place){
			print "                        <option value='".$place['id']."' ".(($place['id']===$event['place_id'])?'selected ':'').">".$place['building']." - ".$place['room']."</option>\n";
		}
		print "                    </select><br>\n";
		print "                    Típus: <select name='style_id' class='form-control' >\n";
		$styles = execquery("SELECT * FROM `styles` ");
		foreach($styles as $style){
			print "                        <option value='".$style['id']."' ".(($style['id']===$event['style_id'])?'selected ':'').">".$style['name']."</option>\n";
		}
		print "                    </select><br>\n";
		print "                    <input type='submit' value='Mentés' class='btn btn-success' />\n";
		print "                </form>\n";
		print "                <br><br>\n";
		if($_GET['edit']!=='0'){
			$alerts = execquery("SELECT * FROM `alerts` WHERE `event_id`=".$event['id']." ");
			foreach($alerts as $alert){
				print "                Figyelmeztetés: ".date('Y.m.d H:i',strtotime($alert['time']))." ".($alert['active']?'':'(kikapcsolva)');
				print "<form action='alert.php' method='POST' style='display: inline;' ><input type='hidden' name='".($alert['active']?'disable':'enable')."' value='".$alert['id']."' ><input type='submit' value='".($alert['active']?'Kikapcsol':'Bekapcsol')."' class='btn btn-info btn-sm' ></form> ";
				print "<form action='alert.php' method='POST' style='display: inline;' ><input type='hidden' name='delete' value='".$alert['id']."' ><input type='submit' value='Törlés' class='btn btn-danger btn-sm' ></form><br><br>\n";
			}
			print "<br><a href='alert.php?create_for=".$event['id']."' class='btn btn-success' >Új figyelmeztetés hozzáadása</a>";
		}
	}else{
		print "                <span>A szerkesztendő esemény nem található.</span>\n";
	}
}elseif(isset($_GET['delete'])){	// delete
	$event = execquery("SELECT * FROM `events` WHERE `id` = ".intval($_GET['delete'])." ");
	if(count($event)===1){
		$event = $event[0];
		execquery("DELETE FROM `events` WHERE `id` = ".$event['id']." ");
		execquery("DELETE FROM `alerts` WHERE `event_id` = ".$event['id']." ");
		redirect("./");
	}else{
		print "                <span>A keresett esemény nem található.</span>\n";
	}
}else{	// list all
	$all = execquery("SELECT `events`.`id` AS 'event_id', `events`.`time` AS 'time', `events`.`title` AS 'title', `places`.`id` AS 'place_id', `places`.`building` AS 'building' FROM `events` JOIN `places` ON `events`.`place_id`=`places`.`id` ");
	if(count($all)<1){
		print "<span>Nincs még egy esemény sem.</span>";
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
		foreach($all as $event){
			print "                        <tr>";
			print '<td>'.date('Y.m.d H:i',strtotime($event['time']))."</td>";
			print '<td><a href="event.php?id='.$event['event_id'].'">'.$event['title'].'</a></td>';
			print '<td><a href="place.php?id='.$event['place_id'].'">'.$event['building'].'</a></td>';
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