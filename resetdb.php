<?php
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
function execquery($query){
	global $mysqli;
	if($result = $mysqli->query($query)){
		if($result === true){
			return array();
		}
		$back = array();
		while($row = $result->fetch_assoc()){
			$back[] = $row;
		}
		$result->free();
		return $back;
	}else{
		print 'sql error: '.$mysqli->errno."\n".$mysqli->error."\n".print_r($mysqli->error_list,true);
		exit();
		return array();	// then witch?
	}
}

execquery("DROP TABLE IF EXISTS `places`;");
execquery("CREATE TABLE `places` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `building` text NOT NULL,
  `room` text NOT NULL,
  `GPS_lat` text NOT NULL,
  `GPS_lon` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;");
execquery("INSERT INTO `places`(`building`,`room`,`GPS_lat`,`GPS_lon`) VALUES ('BME Q','QB234','47.473322','19.059928')");
execquery("INSERT INTO `places`(`building`,`room`,`GPS_lat`,`GPS_lon`) VALUES ('BME I','IB028','47.472771','19.060200')");
execquery("INSERT INTO `places`(`building`,`room`,`GPS_lat`,`GPS_lon`) VALUES ('BME K','K234','47.481858','19.055068')");
execquery("INSERT INTO `places`(`building`,`room`,`GPS_lat`,`GPS_lon`) VALUES ('BME CH','CH MAX','47.482816','19.054358')");
execquery("INSERT INTO `places`(`building`,`room`,`GPS_lat`,`GPS_lon`) VALUES ('BME E','E1B','47.477798','19.057452')");
//execquery("INSERT INTO `places`(`building`,`room`,`GPS_lat`,`GPS_lon`) VALUES ('Otthon','Dolgozó szoba','47.481781','19.031048')");

execquery("DROP TABLE IF EXISTS `styles`");
execquery("CREATE TABLE `styles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `font_size` varchar(64) NOT NULL DEFAULT '14px',
  `font_color` varchar(64) NOT NULL DEFAULT 'black',
  `bg_color` varchar(64) NOT NULL DEFAULT 'white',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;");
execquery("INSERT INTO `styles`(`name`,`font_size`,`font_color`,`bg_color`) VALUES ('default','12px','red','cyan')");
execquery("INSERT INTO `styles`(`name`,`font_size`,`font_color`,`bg_color`) VALUES ('ZH','18px','black','orange')");
execquery("INSERT INTO `styles`(`name`,`font_size`,`font_color`,`bg_color`) VALUES ('pótZH','28px','red','yellow')");
execquery("INSERT INTO `styles`(`name`,`font_size`,`font_color`,`bg_color`) VALUES ('vizsga','18px','white','red')");

execquery("DROP TABLE IF EXISTS `events`");
execquery("CREATE TABLE `events` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `time` datetime NOT NULL,
  `style_id` int(10) unsigned NOT NULL DEFAULT '1',
  `place_id` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;");
execquery("INSERT INTO `events`(`title`,`description`,`time`,`style_id`,`place_id`) VALUES ('Mik-mak ZH','második anyagrészből','2017-05-10 18:00:00',2,5)");
execquery("INSERT INTO `events`(`title`,`description`,`time`,`style_id`,`place_id`) VALUES ('Info2 HF bemutatás','laboron bemutatni','2017-05-11 16:15:00',1,1)");
execquery("INSERT INTO `events`(`title`,`description`,`time`,`style_id`,`place_id`) VALUES ('Méréstechnika ZH','egész éves anyagból','2017-05-12 08:15:00',2,2)");
execquery("INSERT INTO `events`(`title`,`description`,`time`,`style_id`,`place_id`) VALUES ('Üzleti Jog ZH','csak a második rész','2017-05-12 14:00:00',2,1)");
execquery("INSERT INTO `events`(`title`,`description`,`time`,`style_id`,`place_id`) VALUES ('SzabTech elővizsga','ha megvan az aláírás','2017-05-15 08:15:00',4,4)");
execquery("INSERT INTO `events`(`title`,`description`,`time`,`style_id`,`place_id`) VALUES ('Info2 pótZH','NagyZH pótlása','2017-05-16 08:00:00',3,4)");
execquery("INSERT INTO `events`(`title`,`description`,`time`,`style_id`,`place_id`) VALUES ('Info2 konzultáció','vizsgával kapcsolatban','2017-05-23 16:00:00',1,3)");
execquery("INSERT INTO `events`(`title`,`description`,`time`,`style_id`,`place_id`) VALUES ('Info2 vizsga','ha megvan az aláírás','2017-05-24 08:15:00',4,5)");

execquery("DROP TABLE IF EXISTS `alerts`");
execquery("CREATE TABLE `alerts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(10) unsigned NOT NULL,
  `time` datetime NOT NULL,
  `active` tinyint(1) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;");
execquery("INSERT INTO `alerts`(`event_id`,`time`,`active`) VALUES (1,'2017-05-09 20:00:00',1)");
execquery("INSERT INTO `alerts`(`event_id`,`time`,`active`) VALUES (2,'2017-05-10 20:00:00',0)");
execquery("INSERT INTO `alerts`(`event_id`,`time`,`active`) VALUES (2,'2017-05-11 08:00:00',1)");
execquery("INSERT INTO `alerts`(`event_id`,`time`,`active`) VALUES (3,'2017-05-12 07:00:00',1)");
//execquery("INSERT INTO `alerts`(`event_id`,`time`,`active`) VALUES (7,'2017-05-23 10:00:00',1)");

// mysqldump -u root info2_nagyhf > reset.sql

//print "\na";
//var_dump(execquery("SELECT * FROM `events` JOIN `places` ON `events`.`place_id`=`places`.`id` JOIN `styles` ON `events`.`style_id`=`styles`.`id` JOIN `alerts` ON `alerts`.`event_id`=`events`.`id`"));
//print "b\n";

