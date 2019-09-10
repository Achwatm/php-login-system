<?php

session_start();
if (isset($_SESSION['successful_registered'])) {
    header('Location: index.php');
    exit();
} else {
    unset($_SESSION['successful_registered']);
}

?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Osadnicy</title>
</head>

<body>

Dziękujemy za rejestrację w serwisie !
<br/><br/>
<a href="index.php">Zaloguj się na swoje konto!</a>
<br/><br/>


</body>
</html>