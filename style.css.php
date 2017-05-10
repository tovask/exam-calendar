<?php
header('Content-Type: text/css');
?>
html {
	position: relative;
	min-height: 100%;
}
body {
	margin-bottom: 70px; /* margin bottom for footer height */
}
.icon{
	float: left;
	width: 50px;
	height: 50px;
	margin-right: 15px;
	background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAEaUlEQVRoge3XS1BbVRgHcFa6cGE37hy3dUY2Mog2tTAMOBJAikkptVWQJAxYCqS1Q1WS3JBQHjnnAnWwsGlwpTzsFGnldUkCNw+ctirdueoMBBKebdGZznTD30VyU9qQB+SSwJgz823ukP/5fvd+h9ykpCTXIVncjEPG8Y41jnetclbnSbHzJ2zOM1MzznWOd6xxM47TYuencLxjzWKfhcU+i6kZ14rY+VMzrg0hn+Mda2LnpwjhQh22/MMJcHeb8pfqld4lee7Wyxt4Cz8UtYLy5TnwXKxcdneb8vfe/KncrZAb7DfAf33pVO7WnhBL9UqvEOIpCN7AU3hctOYj5Xsuqry7B8if3/257HeDNpjLThMNEDFfnrv7M7F9A16SCss493yD0QnwklTRALwkFZbR8UC+9bexoPyYAdPKz2Edm4RldAI25VnYRQbYKj7zIUbHMF1+Oig/JsBcdhqmj73zQj0QdYQi58cE8BQex1x2GnhJKuySVDzIThP3EEeRHxPgIFQSkOj6/wHWn2ziIFUSkOhKAl5+2YrX9SQgCTgogP0q9/IKppxO9A0MglzvhaGjE4aOTpCeXpgHBmFxuOBeXjl4AO/6BoYnJmHougaGsmHL2HkNOkJMX1y+/NqeAWKOyu1JK9q6f/A1SFh89Q2Dooo6nJCp8OPQCMz9t5ApU+Gkoh7nv9UHIDpC/m4wth9NKOA2Z0Vz1/dgKIuvm64ip7QK6dKyQAl/v/1abmk1LumMWwxloTOZVqNC7AfAs7YeuPM13+nxfuGXLzQaCpAuLcMHn1SgprEp8CQijtN+AIbHJ8FQFpeaWnZsPhxAQAhPQmui7bsCxFoL3mUYOrvAEIqc0uodG4wESJeW4aMz1b6nYCJPa1ta3ogbgHM4wFDfgQ3VXDSAdGlZ4GBr20ld3EbI3D8AhrIoqqiLGVCsqPefBXonbgByvRcMZXFCpooZkClT+ceILsQNYOjoBENZvFdQHra5G0M3cWPoZtDnLfZZ9A0MI11ahoyCct8IEfos7oCM/PCAnp/6Yf5lZ4C5/1b0gNVHT0Q9xLTHN0JZ8sqwgGgqS17pB5D5kICH7kVRAX2DQ2Aoi2KFOmaATKkWvtCGQwLsd+89Xdl4LBrA5vodDGVxodEQM6BW4/tG1hBSFRIwNe0acdz9Ew/di1h9FDtkcWUVzf43z4/Pnt9z83nnanzjYyKbV9raXg8JmLDZ3uZ41+OdDtNey/zzIBjKosHYimNFFbtuXlKkQMPVVuHuN4ZsXlhjVutRjncOc7zrXzEAHO8C6ekBQ1nUa427QkiKFFDrjL7Zp/R+SUnJKxEB+7GutLW9pTPRBeFJ5EUxTnnnatDQ3Br4z9Og17+ZkOa3IzSE/CX8ULnQ2IRihRpZ8kpkFJQjo6AcmTIVPlWqUet/hfaPzR8Jb15Y0traVzWEMDpC/4n0k1JrIptaE9UkbGzCLbVef0TbTqt1hIxoCZnXEvrMV2Re005/1RBSpdbrj4T6/H+PU5zimFmALAAAAABJRU5ErkJggg==');
    background-repeat: no-repeat;
}
table a {
	display: block;
}
th {
	text-align: center;
}
.week-table td {
	width: 14.2%;
}
.footer {
	position: absolute;
	bottom: 0;
	width: 100%;
	height: 70px;	/* set the fixed height of the footer */
}

<?php
require "mylib.php";
$styles = execquery("SELECT * FROM `styles` ");
foreach($styles as $style){
	print ".mystyle-".$style['id']." { /* ".$style['name']." */ \n";
	print "	font-size: ".$style['font_size'].";\n";
	print "	color: ".$style['font_color'].";\n";
	print "	background-color: ".$style['bg_color'].";\n";
	print "}\n";
}
?>