<?php

session_start();
require_once("logic/config.inc.php");
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <link rel="stylesheet" href="style/login.css">
</head>
<body>
<div class="content">
    <div class="container">
        <div class="container-right">
            <h2>Why I owe you?</h2>
            <p>It is not easy to always keep track of debts to and from others. <br>
                <br>
                To keep an overview together, just register with your friends on 'I owe u'. Create a shared account and manage your debts so that you never forget about them.</p>
        </div>
        <div class="container-left">
            <img class="logo" src="logo.png" alt="logo">
            <h2>I owe you</h2>
            <div class="form">
                <h1>Registrieren</h1>
                <form action="logic/action.php" method="POST">
                    <div class="inputbox">
                        <input type="text" name="benutzername" placeholder="Benutzername">
                    </div>
                    <div class="inputbox">
                        <input type="password" name="passwort" placeholder="Passwort">
                    </div>
                    <?php
                    if(isset($_GET['error']) && $_GET['error']==1){
                        echo "<p class='signup'> Nutzername bereits vergeben.</p>";
                    }
                    ?>
                    <div class="center">
                        <input class="btn" type="submit" name="Register" value="Register">
                    </div>
                    <p class="signup">
                        Du hast schon einen Account? <a href="index.php"> Klick hier</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
