<?php

	session_start();
	if(isset($_SESSION['zalogowany']) && ($_SESSION['zalogowany'] = true))
	{
		header('Location: gra.php');
		exit();
	}

?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
<meta charset="utf-8">
<title>Osadnicy</title>
</head>

<body>

	Tylko martwi ujrzeli koniec wojny - Platon
<br/></br>

<form action="logowanie.php" method="post">
Login:<br/>
<input type="text" name="login" /><br/>

Hasło:<br/>
<input type="password" name="haslo" /><br/><br/>

<input type="submit" value="Zaloguj się"/><br/><br/>


</form>
<?php
if(isset($_SESSION['blad']))
{
	
	echo $_SESSION['blad'];
	
}

?>

</body>
</html>