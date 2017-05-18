<?php
		session_start();
		
		if(!isset($_SESSION['name'])){
			header("Location: main_site.php");
		}
		
		if(!isset($_SESSION['new-room'])){
			header("Location: rooms.php");
		}
		
		if(!isset($_SESSION['participants'])){
			$_SESSION['participants'] = array(); 
		}
		
		if($_POST){
			unset($_SESSION['wrong_participant']);
			unset($_SESSION['room_name_error']);
			
			if(isset($_POST['room_name'])) {
				$db = new mysqli("localhost", "root", "", "medical-teleconference");
				$stmt = $db->prepare("select * from rooms where NAME = ?");
				$stmt->bind_param("s", $_POST['room_name']);
				
				$stmt->execute();
				$stmt->store_result();	
				if($stmt->num_rows > 0)
				{
					$_SESSION['room_name_error']="true";
				}else{
					$_SESSION['new-room']=$_POST['room_name'];
				}
				$stmt->close();
				$db->close();
				header("Location: new_room.php");
			}
			
			//Add participant
			if(isset($_POST['participant'])) { 
				if(!in_array($_POST['participant'], $_SESSION['participants']))
				{
					$db = new mysqli("localhost", "root", "", "medical-teleconference");
		
					$stmt = $db->prepare("select * from users where NAME = ?");
					$stmt->bind_param("s", $_POST['participant']);
					
					$stmt->execute();
					$stmt->store_result();	
					if($stmt->num_rows > 0)
					{
						$_SESSION['participants'][] = $_POST['participant'];
						$_SESSION['participant_name'] = "";
					}else{
						$_SESSION['wrong_participant'] = 'true';
						$_SESSION['participant_name'] = $_POST['participant'];
					}
					$stmt->free_result();
					$stmt->close();
					$db->close();
				}else{
					$_SESSION['participant_name'] = "";
				}
				header("Location: new_room.php");
			}
			
			//Delete participant
			if(isset($_POST['delete'])) { 
				unset($_SESSION['participants'][$_POST['delete']]);
				header("Location: new_room.php");
			}
			
			
			//Save
			if(isset($_POST['to_room']) or isset($_POST['save'])) { 
				$db = new mysqli("localhost", "root", "", "medical-teleconference");

				//New room
				$stmt = $db->prepare("select * from rooms where NAME = ?");
				$stmt->bind_param("s", $_SESSION['new-room']);
				
				$stmt->execute();
				$stmt->store_result();	
				if($stmt->num_rows == 0)
				{
					$stmt->free_result();
					
					$db->begin_transaction();
					
					$stmt = $db->prepare("insert into rooms (NAME) values (?)");
					$stmt->bind_param("s", $_SESSION['new-room']);
					$result = $stmt->execute();
					$stmt->free_result();
					
					//Get ROOM_ID and USER_ID
					$stmt = $db->prepare("select ID from rooms where NAME = ?");
					$stmt->bind_param("s", $_SESSION['new-room']);
					$stmt->execute();
					$stmt->store_result();
					
					$stmt->bind_result($ROOM);
					$stmt->fetch();
					$room_id = $ROOM;
					$stmt->free_result();
					
					$stmt = $db->prepare("select ID from users where NAME = ?");
					$stmt->bind_param("s", $_SESSION['name']);
					$stmt->execute();
					$stmt->store_result();
					$stmt->bind_result($USER);
					$stmt->fetch();
					$user_id = $USER;
					$stmt->free_result();
					
					//Add loged in user
					$stmt = $db->prepare("insert into participants (USER_ID, ROOM_ID) values (?, ?)");
					$stmt->bind_param("ii", $user_id, $room_id);
					$result = $stmt->execute();
					$stmt->free_result();
					$stmt->close();
					
					//Add Participants
					$select_user = $db->prepare("select ID from users where NAME=?");
					$insert = $db->prepare("insert into participants (USER_ID, ROOM_ID) values (?, ?)");
					
					foreach($_SESSION['participants'] as $v)
					{
						$select_user->bind_param("s", $v);
						$select_user->execute();
						$select_user->store_result();
						$select_user->bind_result($USER);
						$select_user->fetch();
						$user_id = $USER;
						$select_user->free_result();
						$select_user->close();
						
						$insert->bind_param("ii", $user_id, $room_id);
						$result = $insert->execute();
					}
					
					$insert->close();
					$db->commit();
					$db->close();
					
					if(isset($_POST['to_room'])){
						$_SESSION['room'] = $_SESSION['new-room'];
						header("Location: conference.php");
					}else{
						header("Location: rooms.php");
					}
				}else{
					$_SESSION['room_name_error']="true";
					$db->close();
					header("Location: new_room.php");
				}
			}
			//header("Location: new_room.php");
		}
?>	 

<!DOCTYPE html>
<html>
<head>
     <meta charset="utf-8" />
	 <script src="form_check.js"></script>
     <link rel="stylesheet" type="text/css" href="../css/new_room.css" title="Arkusz stylów CSS">
</head>
<body data-parsley-validate>
     <h1> Witaj <?=$_SESSION['name']?></h1>
	 
	 
		<h2>Dostosuj pokój:</h2>
	<form action="new_room.php" method="post">
		<div>Nazwa: <input type="text" name="room_name" value="<?=$_SESSION['new-room']?>" class="form-control" required=""/>
		<input type="submit" value="Zmień"/>
		<?php 
			if(isset($_SESSION['room_name_error'])){
				echo '<div>Pokój o podanej nazwie już istnieje!</div>';
			}
		?>
		</div>
	</form>
	
	<form action="new_room.php" method="post">
		<h2>Lista uczestników:</h2>
		<ul>
			<?php foreach ($_SESSION['participants'] as $v): ?>
					<li name="delete" value="<?=$v?>"><?=$v?> <input type="submit" value="Usuń"/></li>
			<?php endforeach; ?>
		</ul>
	</form>
	
	<form action="new_room.php" method="post">
			<div>Dodaj uczestników: <input type="text" name="participant" value="<?=$_SESSION['participant_name']?>" class="form-control" required=""/>
				<input type="submit" value="Dodaj"/>
				<?php 
					if(isset($_SESSION['wrong_participant'])){
						echo '<div>Podany uczestnik nie istnieje!</div>';
					}
				?>
			</div>
	</form>
	
	<div>
		<form action="new_room.php" method="post">
				<input type="submit" name="save" value="Utwórz pokój"/>
		</form>
		
		<form action="rooms.php">
				<input type="submit" name="return" value="Powrót"/>
		</form>
		
		<form action="new_room.php" method="post">
				<input type="submit" name="to_room" value="Zapisz i przejdź do pokoju"/>
		</form>
	</div>
	
</body>
</html>