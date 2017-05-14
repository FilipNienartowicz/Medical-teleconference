<?php
		session_start();
		if(!isset($_SESSION['name']))
		{
			header("Location: main_site.php");
		}
		
		if(!isset($_SESSION['room'])){
			header("Location: rooms.php");
		}
?>		 
<!DOCTYPE html>
<html>
<head>
     <meta charset="utf-8" />
	 <script src="form_check.js"></script>
     <link rel="stylesheet" type="text/css" href="../css/conference.css" title="Arkusz stylów CSS">
	
</head>
<body>
	<h1>Telekonferencja</h1>
	<h1> Witaj <?=$_SESSION['name']?></h1>
	<h1> W pokoju <?=$_SESSION['room']?></h1>
</body>
</html>