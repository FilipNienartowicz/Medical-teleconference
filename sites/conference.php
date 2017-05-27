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
  <link rel="stylesheet" type="text/css" href="../css/conference.css" title="Arkusz stylów CSS">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="../scripts/imageTransformation2.js"></script>
</head>
<body onmousedown="return false;">
	<div class="page-wrap">
		<script src="../scripts/header.js"></script>
		<div class="main">
			<div class="welcome col-lg-5 col-md-7 col-sm-10">
				<h1>Witaj <?=$_SESSION['name']?></h1>
				<h1>W pokoju <?=$_SESSION['room']?></h1>
			</div>
		</div>
		<div class="main">
	
			<div class="col-md-6 col-lg-6">
				<div class="row">
					<canvas id="imageToTransform" width="800" height="600"></canvas>
				</div>
				<div class="row">
					<input type="button" id="plus" value="+" />
					<input type="button" id="minus" value="-" />
					<input type="button" id="rotate" value="rotate" />
					<input type="button" id="mix" value="sharpen" />
					<p id="urlWhy">Picture url will appear here</p>
				</div>
				<div class="row">
					<form id="uploadImage" method="post" action="conference.php" enctype="multipart/form-data">
						<input type="file" name="image" id="imageFile" />
						<input type="submit" name="submit" value="Upload" />
					</form>
					<script type="text/javascript" src="../scripts/imageTransformation.js"></script> <!--TODO: tutaj za to nie wiem jak zrobić żeby nie znikało po zapisie do bazy-->
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
							$id = $_SESSION['roomID'];
							$query = "insert into `photos` (`ROOM_ID`, `PHOTO`) values ('$id', '$image')";
							$result = mysql_query($query, $con);
							if($result) {
								echo "<br/> Image uploaded";
							} else {
								echo "<br/> Image not uploaded";
							}
						}
					?>
				</div>
			</div>
		</div>
	</div>
	<script src="../scripts/footer.js"></script>
</body>
</html>
