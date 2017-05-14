 <?php
		session_start();
?>	 
<!DOCTYPE html>
<html>
<head>
     <meta charset="utf-8" />
	 <script src="form_check.js"></script>
     <link rel="stylesheet" type="text/css" href="../css/main_site.css" title="Arkusz stylów CSS">
	
</head>
<body>
     <h1>Telekonsultacje medyczne</h1>
	 <p>Witaj w aplikacji, która pomoże Tobie skonsultować z innymi profesjonalistami zawiłości przypadków medycznych</p>
	 <p>Zanim zaczniemy podaj swoje dane:</p>
	 <form action="main_site.php" method="post">
		<div>Twój nick: <input type="text" name="name" value="" class="form-control" required=""/></div>
		<input type="submit" value="Rozpocznij "/>
	</form>
	
	<?php
	if($_POST){
		
		$db = new mysqli("localhost", "root", "", "medical-teleconference");
		
		$stmt = $db->prepare("select * from users where NAME = ?");
		$stmt->bind_param("s", $_POST['name']);
		
		$stmt->execute();
		$stmt->store_result();	
		if($stmt->num_rows == 0)
		{
			$stmt->free_result();
			$stmt->close();
			$stmt = $db->prepare("insert into users (NAME) values (?)");
			$stmt->bind_param("s", $name);
			$name =  $_POST['name'];
			$result = $stmt->execute();
		}
		$stmt->free_result();
		$stmt->close();
		$db->close();
		
		$_SESSION['name'] = $_POST['name'];
		header("Location: rooms.php");
	}
	?>
</body>
</html>

