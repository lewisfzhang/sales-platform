<?php
	if(isset($_GET['id'])) {
		$url = $_GET["id"]; //the student's unique hash
		$file_db = new PDO('sqlite:quotations.sqlite3'); //the database
		$file_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$find = "SELECT * FROM quotations WHERE url=:url"; //query to find if the hash is in the database
		$stmt = $file_db->prepare($find);
		$stmt->bindParam(':url',$url, SQLITE3_TEXT);
		$result = $stmt->execute();
		$data = $stmt->fetchAll();
		if($data) { //if there is a match w/ the same hash, execute the rest of the code
?>
<html>
<head>
	<title>Bellarmine Senior Quotations</title>
    <!--?-->
	<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script> 
	<script>
        //?
		$(document).ready(function() {
			$("#update").submit(function(event) {
				$.post( "update.php", $( "#update" ).serialize(), function(msg) {
					location.reload(true);
				});
				event.preventDefault();
			});
			function maxLength(el) {    
			    if (!('maxlength' in el)) {
			        var max = el.attributes.maxLength.value;
			        el.onkeypress = function () {
			            if (this.value.length >= max) return false;
			        };
			    }
			}
			maxLength(document.getElementById("quotation"));
		});
        //updates character count
		function countChar(val) {
			var len = val.value.length;
	        $('#charNum').text(100 - len);
		}
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
			<img src="Carillon-Logo.png" class="right" alt="logo"/>
			<div class="left">
				<h1>Hello <?php echo $data[0]["name"]; //writes in your name?></h1>
				<?php $quotation = trim($data[0]["quotation"]);if($quotation!='') { /*Displays your current quotation if you've already entered one*/ ?>
				<p>Your quotation is:</p><br><div class="quote"><?php echo $quotation;?></div><!--</p>-->
				<?php } ?>
			</div>
		</div>
		<div style="clear: both"></div>
		<?php
			if($quotation=='') { 
		?>
		<div>Submit your quotation:</div>
		<?php
			}
		?>
		<?php
            //I think that if the processed varaible = 2, then it was accepted?
			if($data[0]["processedTeacher"]!=2||$data[0]["processedStudent"]!=2) { //if this is true, it was denied
				if($quotation!='') {
		?>
			<div>Change your quotation:</div>
		<?php
		}
		?>
        <!--Simple form to get the user's qtd. and send it via POST to update.php to update the database-->
		<form action="update.php" id="update">
			<input type="hidden" name="id" value="<?php echo $url; ?>" />
			<div class="form-group">
				<textarea maxlength="100" name="quotation" class="form-control" id="quotation" onkeyup="countChar(this)"></textarea>
				<span id="charNum">100</span> characters remaining<br>
				<button type="submit" value="Submit" class="btn btn-default">Submit</button>
			</div>
		</form>
		<div class="alert alert-danger">Please submit by 11:59 pm on Sunday 2/22! Remember to keep it appropriate. This will go below your senior portrait so people will remember you by it. No formatting allowed: no line breaks or emoticons (text-based smilies are allowed).</div>
		
		<?php } ?>
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