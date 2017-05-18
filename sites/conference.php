<?php
		session_start();
		if(!isset($_SESSION['name'])) {
			header("Location: main_site.php");
		}

		if(!isset($_SESSION['room'])) {
			header("Location: rooms.php");
		}
?>
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <meta charset="utf-8" />
  <link rel="stylesheet" type="text/css" href="../css/conference.css" title="Arkusz styl�w CSS">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
	<h1>Telekonferencja</h1>
	<h1> Witaj <?=$_SESSION['name']?></h1>
	<h1> W pokoju <?=$_SESSION['room']?></h1>
	<div class="col-md-6">
		<div class="row">
			<canvas id="imageToTransform"></canvas>
		</div>
		<div class="row">
			<form method="post" enctype="multipart/form-data">
				<input type="file" name="image" id="imageFile" />
				<input type="submit" name="submit" value="Upload" />
			</form>
			<?php
				if(isset($_POST['submit'])) {
					if(getimagesize($_FILES['image']['tmp_name']) == FALSE) {
						echo "Pleas select image";
					} else {
						$image = addslashes($_FILES['image']['tmp_name']);
						$name = addslashes($_FILES['image']['name']);
						$image = file_get_contents($image);
						$image = base64_encode($image);
						saveImageFile($image);
					}
				}
				function saveImageFile($image) {
					$con = mysql_connect("localhost", "root", "");
					mysql_select_db("medical-teleconference", $con);
					$roomName = $_SESSION['room'];
					$query1 = "select ID from rooms where NAME = '$roomName'";
					$result1 = mysql_query($query1, $con);
					echo $result1;
					$value = mysql_fetch_field($result1);
					$query = "insert into photos (ROOM_ID, PHOTO) values ('2', '$image')"; //TODO: nie wiem jak to id znaleźć bo z selecta wychodzi "resource id #7"
					$result = mysql_query($query, $con);
					if($result) {
						echo "<br/> Image uploaded";
					} else {
						echo "<br/> Image not uploaded";
					}
				}
			?>
			<script type="text/javascript" src="../scripts/imageTransformation.js"></script> <!--TODO: tutaj za to nie wiem jak zrobić żeby nie znikało po zapisie do bazy
		</div>
	</div>
</body>
</html>
