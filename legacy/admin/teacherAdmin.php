<?php
	include 'users.php';
	$user = $_SERVER['PHP_AUTH_USER'];
	$pass = $_SERVER['PHP_AUTH_PW'];
	$validated = ($user==$valid_user)&&($pass==$valid_password);
	if($validated) {
?>
<html>
<head>
	<title>Admin</title>
	<style>
		body {
			font-size:24px;
		}
	</style>
	<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<script>
		var currUrl = "";
		var content = [];
		document.onkeydown = function(e) {
			if(e.keyCode==38) {
				$("#status").text("Updating");
				$.post( "teacherChange.php", {pass:1, id:currUrl}, function(msg) {
					$("#status").text("Updated");
					update();
				});
			}
			if(e.keyCode==40) {
				$("#status").text("Updating");
				var reason = prompt("Reason:");
				$.post( "teacherChange.php", {pass:0, id:currUrl, reason:encodeURIComponent(reason)}, function(msg) {
					$("#status").text("Updated");
					update();
				});
			}
		}
		function update() {
			$.get( "teacherProcess.php", function(msg) {
				if(msg.trim()!="{}") {
					content = $.parseJSON(msg);
					console.log(content);
					index = 0;
					currUrl = content["url"];
					$("#requested").text(content["quotation"]);
					$("#name").text(content["name"]);
				}
				else {
					currUrl = "";
					$("#requested").text("");
					$("#name").text("");
				}
			});
		}
		$(document).ready(function() {
			update();
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
				<h1 id="name"></h1>
				<div>Requested:</div><div id="requested" class="quote"></div><br>
			</div>
		</div>
		<p id="status" class="label label-default">Press up arrow to approve, down arrow to reject.</p>
	</div>
</body>
</html>
<?php
	}
	else {
		header('WWW-Authenticate: Basic realm="Carillon"');
		header('HTTP/1.0 401 Unauthorized');
		die ("Not authorized");
	}
?>