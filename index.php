<?php
	require_once "function.php";
?>
<!DOCTYPE html>
 <html>
 <head>
 	<title>Tic Tac Toe</title>
 	<meta charset="utf-8">
 	 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 	<link rel="stylesheet" type="text/css" href="style.css">
 	<link rel="stylesheet" type="text/css" href="ermile.css">
 	<link rel="shortcut icon" type="image/x-icon" href="images/bgname.png">
 </head>
 <body
 	<?php
 		if(isset($_GET['action']) && $_GET['action'] == 'set_name')
 			echo "id='set_name_body'"; 
 	?>
 >
 	<div class="container">
 		<form method="post" id="game">
	 		<?php
	 			game();
	 		?>
 		</form>
 	</div>
 	<footer>
 		<span>
 			Designed by AmirhosseinAbbaskohi
 		</span>
 		<span>
 			<a href="https://www.google.com">
 				My github
 			</a>
 		</span>
 	</footer>
 </body>
 </html>