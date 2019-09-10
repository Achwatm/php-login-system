<?php

session_start();

if (isset($_POST['email'])) {
    //udana walidacja?
    $valid = true;

    //Sprawdź poprawność nick
    $nick = $_POST['nick'];
    if (strlen($nick) < 3 || (strlen($nick) > 20)) {
        $valid = false;
        $_SESSION['e_nick'] = "Nick musi posiadać od 3 do 20 znaków!";
    }
    if (ctype_alnum($nick) == false) {
        $valid = false;
        $_SESSION['e_nick'] = "Nick może składać się z liter i cyfr (bez polskich znaków)!";
    }


    //Sprawdź poprawność email

    $email = $_POST['email'];
    $safeEmail = filter_var($email, FILTER_SANITIZE_EMAIL);

    if (filter_var($safeEmail, FILTER_VALIDATE_EMAIL) == false || ($email != $safeEmail)) {
        $valid = false;
        $_SESSION['e_email'] = 'Podaj poprawny adres email';
    }

    //Sprawdź poprawność hasła
    $password = $_POST['password'];
    $rPassword = $_POST['rPassword'];

    if (strlen($password) < 8 || (strlen($password) > 20)) {
        $valid = false;
        $_SESSION['e_password'] = "Hasło musi posiadać od 8 do 20 znaków!";
    }

    if ($password != $rPassword) {
        $valid = false;
        $_SESSION['e_password'] = "Podane hasła nie są identyczne";
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Sprawdzanie reg

    if (!isset($_POST['statute'])) {
        $valid = false;
        $_SESSION['e_statute'] = "Regulamin nie został zaakceptowany!";
    }

    //reCaptcha

    $secret = "6LewlrcUAAAAAEiq4V62rOtOUWwpye945RdRG4WE";

    $check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secret . '&response=' . $_POST['g-recaptcha-response']);


    $response = json_decode($check);

    if ($response->success == false) {
        $valid = false;
        $_SESSION['e_bot'] = "Potwierdź że nie jesteś botem!";
    }

    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
        if ($polaczenie->connect_errno != 0) {
            throw new Exception(mysqli_connect_errno());
        } else {
            //Czy email już istnieje ?
            $result = $polaczenie->query("Select id FROM uzytkownicy WHERE email='$email'");
            if (!$result) {
                throw new Exception($polaczenie->error);
            }
            $count_email_rows = $result->num_rows;
            if ($count_email_rows > 0) {
                $valid = false;
                $_SESSION['e_email'] = "Podany e-mail jest już przypisany do innego konta !";
            }

            //Czy login już istnieje ?
            $result = $polaczenie->query("Select id FROM uzytkownicy WHERE user='$nick'");
            if (!$result) {
                throw new Exception($polaczenie->error);
            }
            $count_nick_rows = $result->num_rows;
            if ($count_nick_rows > 0) {
                $valid = false;
                $_SESSION['e_nick'] = "Użytkownik o podanym nicku już istnieje!";
            }
            if ($valid == true) {
                if ($polaczenie->query("Insert into uzytkownicy values (NULL,'$nick','$password_hash','$email',100,100,100,14)")) {
                    $_SESSION['successful_registerd'] = true;
                    header('Location: witamy.php');
                } else {
                    throw new Exception($polaczenie->error);
                }
                exit();
            }
            $polaczenie->close();
        }
    } catch (Exception $e) {
        echo '<span style="color:red;">Błąd servera! Przepraszamy za niedogodności</span>';
        echo '<br/>Informacja developerska: ' . $e;
    }
}

?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Osadnicy - załóż darmowe konto</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        .error {
            color: red;
            margin: 10px 0;
        }
    </style>
</head>

<body>

<form method="post">
    Nickname:<br/><input type="text" name="nick"/><br/>
    <?php
    if (isset($_SESSION['e_nick'])) {
        echo '<div class="error">' . $_SESSION['e_nick'] . '</div>';
        unset($_SESSION['e_nick']);
    }
    ?>
    Email:<br/><input type="text" name="email"/><br/>
    <?php
    if (isset($_SESSION['e_email'])) {
        echo '<div class="error">' . $_SESSION['e_email'] . '</div>';
        unset($_SESSION['e_email']);
    }
    ?>
    Password:<br/><input type="password" name="password"/><br/>
    <?php
    if (isset($_SESSION['e_password'])) {
        echo '<div class="error">' . $_SESSION['e_password'] . '</div>';
        unset($_SESSION['e_password']);
    }
    ?>
    Repeat Password:<br/><input type="password" name="rPassword"/><br/>
    <label>
        <input type="checkbox" name="statute"> Akceptuje regulamin <br/>
    </label>
    <?php
    if (isset($_SESSION['e_statute'])) {
        echo '<div class="error">' . $_SESSION['e_statute'] . '</div>';
        unset($_SESSION['e_statute']);
    }
    ?>
    <div class="g-recaptcha" data-sitekey="6LewlrcUAAAAAHjyc_WwEBbHb2lEyBA5J3I0BACM"></div>
    <br/>
    <?php
    if (isset($_SESSION['e_bot'])) {
        echo '<div class="error">' . $_SESSION['e_bot'] . '</div>';
        unset($_SESSION['e_bot']);
    }
    ?>
    <input type="submit" value="Zarejestruj się"/><br/>

</form>
</body>
</html>