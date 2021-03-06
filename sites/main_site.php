 <?php
		session_start();
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
<!DOCTYPE html>
<html>
<head>
     <meta charset="utf-8" />
	 <script src="form_check.js"></script>
     <link rel="stylesheet" type="text/css" href="../css/main_site.css" title="Arkusz stylów CSS">
	
</head>
<body>
	<div class="page-wrap">
		 <script src="../scripts/header.js"></script>
		<div class="main"> 
			<div class="field backcolor-lightgreen col-lg-4 col-md-6 col-sm-10">
					 <form action="main_site.php" method="post">
						<div class="cont">
							<h2>Zanim zaczniemy podaj swoje dane:</h2>		
							<h3>Twój nick: </h3>
							<input type="text" name="name" value="" class="form-control" required=""/>
							<button class="button button-color-goto">Rozpocznij</button>
						</div>
					</form>
			</div>
		</div>
	</div>
	<script src="../scripts/footer.js"></script>
</body>
</html>

