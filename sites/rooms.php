<?php
		session_start();
		if(!isset($_SESSION['name']))
		{
			header("Location: main_site.php");
		}
		
		if($_POST){
			$_SESSION['room'] = $_POST['room'];
			header("Location: conference.php");
		}
?>	 

<!DOCTYPE html>
<html>
<head>
     <meta charset="utf-8" />
	 <script src="form_check.js"></script>
     <link rel="stylesheet" type="text/css" href="../css/rooms.css" title="Arkusz stylów CSS">
</head>
<body data-parsley-validate>
     <h1> Witaj <?=$_SESSION['name']?></h1>
	 <h2>Pokoje, do których masz dostęp:</h2>
	 
	 <form action="rooms.php" method="post">
	 <ul>
		 <?php
			$db = new mysqli("localhost", "root", "", "medical-teleconference");
			$db -> query ('SET NAMES utf8');
			$db -> query ('SET CHARACTER_SET utf8_polish_ci');
			
			$stmt = $db->prepare("select NAME from rooms where id IN 
				(select p.ROOM_ID from users as u 
				join participants as p 
				on u.ID=p.USER_ID where NAME = ?)");
			$stmt->bind_param("s", $_SESSION['name']);
			$stmt->execute();
			$stmt->bind_result($NAME);
			$stmt->store_result();	
			
			while($stmt->fetch()){
				echo '<li>' . $NAME . '  <input type="submit" value="'. $NAME .'" name="room"/>' . '</li>';
			}
			
			$stmt->free_result();
			$stmt->close();
			$db->close();
		?>
	 </ul>
	 </form>
	 
	 <form action="rooms.php">
		<h2>Załóż nowy pokój:</h2>
		<div>Nazwa:<input type="text" name="new-room" value="" class="form-control" required=""/></div>
		<h2>Lista uczestników:</h2>
		<div>Dodaj uczestników: <input type="text" name="participant" value=""/></div>
		<input type="submit" value="Załóż "/>
	</form>
</body>
</html>