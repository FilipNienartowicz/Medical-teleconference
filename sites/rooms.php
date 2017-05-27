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
				$_SESSION['roomID'] = $_POST['roomID'];
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
     <div class="page-wrap">
		 <script src="../scripts/header.js"></script>
		 <div class="main">
			<h1 class="welcome col-lg-5 col-md-7 col-sm-10"> Witaj <?=$_SESSION['name']?></h1>
		</div>
		 <div class="main col-lg-12 col-md-12 col-sm-12">
			 <div class="field backcolor-lightgreen col-lg-4 col-md-6 col-sm-8">
				<h2>Pokoje, do których masz dostęp:</h2>
				 <?php
					$db = new mysqli("localhost", "root", "", "medical-teleconference");
					$db -> query ('SET NAMES utf8');
					$db -> query ('SET CHARACTER_SET utf8_polish_ci');

					$stmt = $db->prepare("select ID, NAME from rooms where id IN
						(select p.ROOM_ID from users as u
						join participants as p
						on u.ID=p.USER_ID where NAME = ?)");
					$stmt->bind_param("s", $_SESSION['name']);
					$stmt->execute();
					$stmt->bind_result($ID, $NAME);
					$stmt->store_result();

					while($stmt->fetch()){
						echo '
						<form action="rooms.php" method="post" class="cont border backcolor-midgreen">
							<h2>' . $NAME . '</h2>
							<input type="hidden" value="'. $ID .'" name="roomID"/>
							<button value="'. $NAME .'" name="room" class="button button-color-goto">Przejdź do pokoju</button>
						</form>';
					}

					$stmt->free_result();
					$stmt->close();
					$db->close();
				?>
				</div>
			 <form action="rooms.php" method="post" class="field backcolor-lightblue col-lg-3 col-md-3 col-sm-6">
				<div class="cont">
					<h2>Nazwa:</h2>
					<input type="text" name="new-room" value="<?=$_SESSION['new_room_name']?>" class="form-control col-lg-6 col-md-6 col-sm-6 a" required=""/>
					<button class="button button-color-add ">Załóż nowy pokój</button>
					<?php
						if(isset($_SESSION['new_room_error'])){
							echo '<div class="error"><div class="errcont">Pokój o podanej nazwie już istnieje!</div></div>';
						}
					?>
				</div>
			</form>
		</div>
	</div>
	<script src="../scripts/footer.js"></script>
</body>
</html>
