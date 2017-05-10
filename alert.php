<?php
require "before_content.php";
?>
<?php
if(isset($_GET['insert_for']) && isset($_POST['time']) && count(execquery("SELECT `id` FROM `events` WHERE `id`=".intval($_GET['insert_for'])))===1 ){	// insert into database, and redirect back to the event
	execquery("INSERT INTO `alerts` (`event_id`,`time`,`active`) VALUES ('".intval($_GET['insert_for'])."','".$mysqli->real_escape_string($_POST['time'])."','1') ");
	redirect("event.php?edit=".intval($_GET['insert_for']));
}elseif(isset($_GET['create_for'])){	// form for create new
	print "                <h1>Új figyelmeztetés</h1>\n";
	print "                <form action='alert.php?insert_for=".$_GET['create_for']."' method='POST' >\n";
	print "                    Időpont: <input type='text' name='time' value='".mysql_date(get_today_first_time()+30*60*60)."' class='form-control' /><br>\n";
	print "                    <input type='submit' value='Mentés' class='btn btn-success' />\n";
	print "                </form>\n";
}elseif(isset($_POST['enable'])){	// enable
	$alert = execquery("SELECT * FROM `alerts` WHERE `id` = ".intval($_POST['enable'])." ");
	if(count($alert)===1){
		$alert = $alert[0];
		execquery("UPDATE `alerts` SET `active`=1 WHERE `id` = ".intval($_POST['enable'])." ");
		redirect("event.php?edit=".$alert['event_id']);
	}else{
		print "                <span>A keresett figyelmeztetést nem található.</span>\n";
	}
}elseif(isset($_POST['disable'])){	// disable
	$alert = execquery("SELECT * FROM `alerts` WHERE `id` = ".intval($_POST['disable'])." ");
	if(count($alert)===1){
		$alert = $alert[0];
		execquery("UPDATE `alerts` SET `active`=0 WHERE `id` = ".intval($_POST['disable'])." ");
		redirect("event.php?edit=".$alert['event_id']);
	}else{
		print "                <span>A keresett figyelmeztetést nem található.</span>\n";
	}
}elseif(isset($_POST['delete'])){	// delete
	$alert = execquery("SELECT * FROM `alerts` WHERE `id` = ".intval($_POST['delete'])." ");
	if(count($alert)===1){
		$alert = $alert[0];
		execquery("DELETE FROM `alerts` WHERE `id` = ".intval($_POST['delete'])." ");
		redirect("event.php?edit=".$alert['event_id']);
	}else{
		print "                <span>A keresett figyelmeztetést nem található.</span>\n";
	}
}else{	// list all
	$all = execquery("SELECT `alerts`.`time` as 'time', `alerts`.`active` as 'active', `events`.`id` as 'event_id', `events`.`title` as 'title' FROM `alerts` JOIN `events` ON `alerts`.`event_id`=`events`.`id` ORDER BY `alerts`.`time` ");
	if(count($all)<1){
		print "<span>Nincs még egy figyelmeztetés sem.</span>";
	}else{
		print "                <table class='table table-striped text-center'>\n";
		print "                    <thead>\n";
		print "                        <tr>\n";
		print "                            <th>Időpont</th>\n";
		print "                            <th>Esemény</th>\n";
		print "                            <th>Állapot</th>\n";
		print "                        </tr>\n";
		print "                    </thead>\n";
		print "                    <tbody>\n";
		foreach($all as $alert){
			print "                        <tr>";
			print '<td>'.date('Y.m.d H:i',strtotime($alert['time'])).'</td>';
			print '<td><a href="event.php?id='.$alert['event_id'].'">'.htmlspecialchars($alert['title'],ENT_QUOTES).'</a></td>';
			print '<td>'.($alert['active']?'Bekapcsolva':'Kikapcsolva').'</td>';
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