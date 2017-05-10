<?php
require "before_content.php";
?>
<?php
$attributes = ['name','font_size','font_color','bg_color'];
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
		execquery("INSERT INTO `styles` (`".implode('`,`',$attributes)."`) VALUES ('".implode("','",$values)."') ");
		$_GET['id'] = db_last_inserted_id();
	}else{	// modify
		foreach($attributes as $attribute){
			if(isset($_POST[$attribute])){
				execquery("UPDATE `styles` SET `".$attribute."` = '".$mysqli->real_escape_string($_POST[$attribute])."' WHERE `id`=".intval($_GET['id'])." ");
			}
		}
	}
	$style = execquery("SELECT * FROM `styles` WHERE `id` = ".intval($_GET['id'])." ");
	if(count($style)===1){
		$style = $style[0];
		$can_delete = count(execquery("SELECT * FROM `events` WHERE `style_id` = ".$style['id']." ")) === 0;
		print "                <a href='style.php?edit=".$style['id']."' class='btn btn-warning' >Szerkesztés</a>\n";
		print "                <a href='style.php?delete=".$style['id']."' class='btn btn-danger".($can_delete?"":" disabled")."' ".($can_delete?"":"title='Nem törölheted, amíg hivatkozik rá esemény!' ").">Törlés</a>\n";
		if(!$can_delete){
			print "                (Nem lehet törölni, amíg hivatkozik rá esemény!)\n";
		}
		print "                <h2>".htmlspecialchars($style['name'],ENT_QUOTES)."</h2>\n";
		print "                <h3>".htmlspecialchars($style['font_size'],ENT_QUOTES)."</h3>\n";
		print "                <h3>".htmlspecialchars($style['font_color'],ENT_QUOTES)."</h3>\n";
		print "                <h3>".htmlspecialchars($style['bg_color'],ENT_QUOTES)."</h3>\n";
		// a preview?
	}else{
		print "                <span>A keresett típus nem található.</span>\n";
	}
}elseif(isset($_GET['edit'])){	// edit or create
	if($_GET['edit']==='0'){	// new one
		print "                <h1>Új típus</h1>\n";
		$style = [ [ 'id'=>0 , 'name'=>'','font_size'=>'14px','font_color'=>'black','bg_color'=>'white'] ];
	}else{
		$style = execquery("SELECT * FROM `styles` WHERE `id` = ".intval($_GET['edit'])." ");
	}
	if(count($style)===1){
		$style = $style[0];
		print "                <form action='style.php?id=".$style['id']."' method='POST' >\n";
		print "                    Név: <input type='text' name='name' value='".htmlspecialchars($style['name'],ENT_QUOTES)."' class='form-control' /><br>\n";
		print "                    Betü méret: <input type='text' name='font_size' value='".htmlspecialchars($style['font_size'],ENT_QUOTES)."' class='form-control' /><br>\n";
		print "                    Betü szín: <input type='text' name='font_color' value='".htmlspecialchars($style['font_color'],ENT_QUOTES)."' class='form-control' /><br>\n";
		print "                    Háttér szín: <input type='text' name='bg_color' value='".htmlspecialchars($style['bg_color'],ENT_QUOTES)."' class='form-control' /><br>\n";
		print "                    <input type='submit' value='Mentés' class='btn btn-success' />\n";
		print "                </form>\n";
	}else{
		print "                <span>A szerkesztendő típus nem található.</span>\n";
	}
}elseif(isset($_GET['delete'])){	// delete
	$style = execquery("SELECT * FROM `styles` WHERE `id` = ".intval($_GET['delete'])." ");
	if(count($style)===1){
		$style = $style[0];
		$can_delete = count(execquery("SELECT * FROM `events` WHERE `style_id` = ".$style['id']." ")) === 0;
		if($can_delete){
			execquery("DELETE FROM `styles` WHERE `id` = ".$style['id']." ");
		}
		redirect("./style.php");
	}else{
		print "                <span>A keresett stílus nem található.</span>\n";
	}
}else{	// list all
	$all = execquery("SELECT * FROM `styles` ");
	if(count($all)<1){
		print "<span>Nincs még egy típus sem.</span>";
	}else{
		print "                <table class='table table-striped text-center'>\n";
		print "                    <thead>\n";
		print "                        <tr>\n";
		print "                            <th>Név</th>\n";
		print "                            <th>Betü méret</th>\n";
		print "                            <th>Betü szín</th>\n";
		print "                            <th>Háttér szín</th>\n";
		print "                        </tr>\n";
		print "                    </thead>\n";
		print "                    <tbody>\n";
		foreach($all as $style){
			print "                        <tr>";
			foreach($attributes as $attribute){
				print '<td><a href="style.php?id='.$style['id'].'">'.htmlspecialchars($style[$attribute],ENT_QUOTES).'</a></td>';
			}
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