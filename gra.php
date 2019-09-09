<?php

	session_start();
	
	if(!isset($_SESSION['zalogowany']))
	{
		header('Location: index.php');
		exit();
	}

?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
<meta charset="utf-8">
<title>Osadnicy - gra przeglądarkowa</title>
</head>

<body>
<?php


				
				echo "Witaj ".$_SESSION['user'].'![<a href="logout.php">Wyloguj się</a>]<br/>' ;
				echo "<b>DREWNO: </b>".$_SESSION['wood']." | <b>Kamień: </b>".$_SESSION['stone']." | <b>Zboże: </b>".$_SESSION['corn']." <br/><br/>";
				echo "<b>E-mail: </b>".$_SESSION['email']."<br/>";
				echo"<b>Dni premium: </b>".$_SESSION['premium_days'];


?>

</body>
</html>