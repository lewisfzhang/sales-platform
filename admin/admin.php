<?php
	include 'users.php';
	$user = $_SERVER['PHP_AUTH_USER'];
	$pass = $_SERVER['PHP_AUTH_PW'];
	$validated = ($user==$valid_user)&&($pass==$valid_password);
	if($validated) {
		if(isset($_GET['id'])) {
			$url = $_GET["id"];
			$file_db = new PDO('sqlite:../quotations.sqlite3');
			$file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$find = "SELECT * FROM quotations WHERE url=:url";
			$stmt = $file_db->prepare($find);
			$stmt->bindParam(':url',$url, SQLITE3_TEXT);
			$result = $stmt->execute();
			$data = $stmt->fetchAll();
			if($data) {
?>
<html>
<head>
	<title>ManuallyAdmin</title>
	<style>
		body {
			font-size:24px;
		}
	</style>
	<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<script>
		$(document).ready(function() {
			$("#update").submit(function(event) {
				$.post( "adminChange.php", $( "#update" ).serialize(), function(msg) {
					location.reload(true);
				});
				event.preventDefault();
			});
		});
	</script>
	<style>
		.right{
			float: right;
			height: 150px;
		}
		.content{
			margin: 0 auto;
			width:600px;
		}
		.quote{
			background-color: #F6F6F6;
			padding: 10px;
			border-radius: 5px;
		}
		.left *{
			width: 440px;
			word-wrap: break-word;
		}
	</style>
</head>
<body>
	<div class="content">
		<div>
			<img src="../Carillon-Logo.png" class="right"/>
			<div class="left">
				<h1>Quotation: <?php echo $data[0]["name"];?></h1>
				<?php $quotation = trim($data[0]["quotation"]);if($quotation!='') { ?>
				<p>His quotation is:<br><div class="quote"><?php echo $quotation;?></div></p>
				<?php } ?>
			</div>
		</div>
		<form id="update">
			<input type="hidden" name="id" value="<?php echo $url; ?>" />
			<div class="form-group">
				<textarea name="quotation" class="form-control" id="quotation" onkeyup="countChar(this)"></textarea>
				<button type="submit" value="Submit" class="btn btn-default">Submit</button>
			</div>
		</form>
	</div>
</body>
</html>
<?php 		} 
			else 
				echo "404" ;
	}
	else {
		echo "Please check your email for a customized URL.";
	}
?>
<?php
	}
	else {
		header('WWW-Authenticate: Basic realm="Carillon"');
		header('HTTP/1.0 401 Unauthorized');
		die ("Not authorized");
	}
?>