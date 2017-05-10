<?php
// on a live site, these should be disabled!
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// setup the sql connection
$mysqli = new mysqli("localhost", "info2_nagyhf", "6LLHzhsew6xHJKds", "info2_nagyhf");
if($mysqli->connect_errno){
	print 'Connect error: '.$mysqli->connect_errno."\n";
	print $mysqli->connect_error;
	exit();
}
$mysqli->set_charset('utf8');
// guaranteed to return an array, even if a sql error occured
// if $assoc_with_id==true, then the the result is an associative array, where the key is the row id (which should exists in the query result)
function execquery($query, $assoc_with_id=false){
	//var_dump($mysqli->real_escape_string("a \n b \r c ' d \" e \\ f árvíztűrőtükörfúrógép ÁRVÍZTŰRŐTÜKÖRFÚRÓGÉP "));
	global $mysqli;
	if($result = $mysqli->query($query)){
		if($result === true){
			return array();
		}
		$back = array();
		if($assoc_with_id){	// use id as key
			while($row = $result->fetch_assoc()){
				$back[ $row['id'] ] = $row;
			}
		}else{
			while($row = $result->fetch_assoc()){
				$back[] = $row;
			}
		}
		$result->free();
		return $back;
	}else{
		print 'sql error: '.$mysqli->errno."\n".$mysqli->error."\n".print_r($mysqli->error_list,true);
		exit();
		return array();	// then witch?
	}
}
// return the last inserted record's unique id
function db_last_inserted_id(){
	global $mysqli;
	return intval($mysqli->insert_id);
}
/*echo "<!-- \n";
var_dump($mysqli->set_charset('utf8'));
print "\n";
echo $mysqli->character_set_name()."\n";
echo $mysqli->real_escape_string("a ' b \" c \\ d \n árvíztűrőtükörfúrógép ÁRVÍZTŰRŐTÜKÖRFÚRÓGÉP ");
var_dump(execquery("SELECT ' árvíztűrőtükörfúrógép ÁRVÍZTŰRŐTÜKÖRFÚRÓGÉP ' FROM DUAL "));
var_dump(execquery("INSERT INTO `styles` (`name`,`font_size`,`font_color`,`bg_color`) VALUES (' árvíztűrőtükörfúrógép ÁRVÍZTŰRŐTÜKÖRFÚRÓGÉP ','14px','black','white')"));
var_dump(execquery("SELECT ' árvíztűrőtükörfúrógép ÁRVÍZTŰRŐTÜKÖRFÚRÓGÉP ' FROM `styles` WHERE `id`=".db_last_inserted_id()));
var_dump(execquery("SELECT * FROM `styles` WHERE `id`=".db_last_inserted_id()));
echo "\n -->\n";*/

date_default_timezone_set('Europe/Budapest');
setlocale(LC_ALL, 'hu_HU.UTF8');
function get_today_first_time(){
	return time() - time() % (24*60*60) + -2*60*60;	// timezone fix
}
function mysql_date($time){	// format timestamp to mysql format
	return date("Y-m-d H:i:s",$time);
}

function redirect($url){
	print "<script>window.location = '".$url."';</script>";
}
