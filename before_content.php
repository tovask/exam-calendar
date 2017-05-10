<?php
require "mylib.php";
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	
	<!-- Include bootstrap and jquery (http://getbootstrap.com/css/) -->
	<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
	<script type="text/javascript" src="//code.jquery.com/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	
	<!-- Custom style -->
	<link rel="stylesheet" type="text/css" href="style.css.php">
	
	<title>Info 2 NagyHF</title>
	
	<script>
		window.onload = function(){
<?php
$alerts = execquery("SELECT alerts.id AS id, events.title as title, events.time as time FROM `alerts` JOIN `events` ON `alerts`.`event_id` = `events`.`id` AND `alerts`.`time`<='".mysql_date(time())."' AND `alerts`.`active`=1 ");
foreach($alerts as $alert){
	execquery("UPDATE alerts SET active=0 WHERE id=".$alert['id']);
	print "			alert('".$alert['title'].":\\n".$alert['time']."');\n";
}
?>
		};
	</script>
	
</head>
<body>
	<!-- Header -->
	<nav class="navbar navbar-inverse">
	  <div class="container-fluid">
		<div class="navbar-header">
		  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		  </button>
		  <span class="icon" ></span>
		  <a class="navbar-brand" href="./">Követelmény naptár</a>
		</div>
		<div id="navbar" class="navbar-collapse collapse">
		  <ul class="nav navbar-nav navbar-right">
			<li><a href="event.php?edit=0" >Új esemény</a></li>
			<li><a href="style.php?edit=0" >Új stílus</a></li>
			<li><a href="place.php?edit=0" >Új helyszín</a></li>
		  </ul>
		  <form class="navbar-form navbar-right" action="search.php" method="GET" >
			<input type="text" name="q" class="form-control" placeholder="Keresés..." />
		  </form>
		</div>
	  </div>
	</nav>
	
	<div class="container-fluid">
		<div class="row">
			<!-- Menu -->
			<div class="col-sm-12 col-md-2" style="border-right: 3px solid #ccc;" >
				<ul class="nav nav-sidebar">
					<li><a href="./" >Főoldal</a></li>
					<li><a href="search.php" >Keresés</a></li>
					<!-- <li><a href="event.php" >Összes esemény</a></li> -->
					<li><a href="style.php" >Stílusok</a></li>
					<li><a href="place.php" >Helyszínek</a></li>
					<li><a href="alert.php" >Figyelmeztetések</a></li>
				</ul>
			</div>
			<!-- Content -->
			<div class="col-sm-12 col-md-10">
