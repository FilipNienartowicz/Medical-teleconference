<?php
		session_start();
		unset($_SESSION['new-room']);
		if(!isset($_SESSION['name'])){
			header("Location: main_site.php");
		}
		
		if(!isset($_SESSION['new_room_name'])){
			$_SESSION['new_room_name'] = "";
		}
		
		
		if($_POST){
			unset($_SESSION['new_room_error']);
			if(isset($_POST['room'])) { 
				$_SESSION['room'] = $_POST['room'];
				header("Location: conference.php");
			}
			
			if(isset($_POST['new-room'])) { 
				$db = new mysqli("localhost", "root", "", "medical-teleconference");
				$stmt = $db->prepare("select * from rooms where NAME = ?");
				$stmt->bind_param("s", $_POST['new-room']);
				
				$stmt->execute();
				$stmt->store_result();	
				if($stmt->num_rows > 0)
				{
					$_SESSION['new_room_name'] = $_POST['new-room'];
					$_SESSION['new_room_error']="true";
					$stmt->close();
					$db->close();
					header("Location: rooms.php");
				}else{
					unset($_SESSION['room_name_error']);
					unset($_SESSION['new_room_error']);
					unset($_SESSION['participants']);
					$_SESSION['participant_name']="";
					$_SESSION['new-room'] = $_POST['new-room'];
					$stmt->close();
					$db->close();
					header("Location: new_room.php");
				}
			}
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
				echo '<li><input type="submit" value="'. $NAME .'" name="room"/>' . '</li>';
			}
			
			$stmt->free_result();
			$stmt->close();
			$db->close();
		?>
	 </ul>
	 </form>
	 
	 <form action="rooms.php" method="post">
		<div>Nazwa: <input type="text" name="new-room" value="<?=$_SESSION['new_room_name']?>" class="form-control" required=""/></div>
		<input type="submit" value="Załóż nowy pokój"/>
		<?php 
			if(isset($_SESSION['new_room_error'])){
				echo '<div>Pokój o podanej nazwie już istnieje!</div>';
			}
		?>
	</form>
</body>
</html>