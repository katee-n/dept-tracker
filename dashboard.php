<?php

session_start();
require_once("logic/config.inc.php");

$users = new User();
$accounts = new Accounts();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="style/dashboard.css">
</head>
<body>

<div class="content">

    <div class="header">
        <div class="div-logo">
            <img src="logo.png" class="logo-img" alt="logo">
            <h3>I owe you</h3>
        </div>
        <div class="greeting">
            <h1>Hello <?=$_SESSION['name'];?>, this is your dashboard.</h1>
        </div>
        <div class="user-logout">
            <img src="user.png" alt="user">
            <div class="user-data">
                <h3><?=$_SESSION['name'];?></h3>
                <form action="logic/action.php" method="POST">
                    <input  class="logout-btn" type="submit" name="Logout" value="Logout">
                </form>
            </div>
        </div>

    </div>

    <h2>Overview</h2>
    <div class="row">
        <div class="panel overview-card good-card">
            <h3>Credits:</h3>
            <p><?=$accounts->getIncome($_SESSION['name']);?> €</p>
        </div>
        <div class="panel overview-card bad-card">
            <h3>Depts:</h3>
            <p><?=$accounts->getDepts($_SESSION['name']);?> €</p>
        </div>
        <div class="panel overview-card">
            <h3>Balance:</h3>
            <p><?=$accounts->getBalance($_SESSION['name']);?> €</p>
        </div>
    </div>

    <div class="addStuff">
        <div>
            <h2>Add new account</h2>
            <div class="panel">
                <form action="logic/action.php" method="POST">
                    <label for="person">Person:</label>
                    <select name="person" >
                        <option selected="person">Choose one</option>
                        <?php
                        foreach($users->getNewFriends($_SESSION['userId']) as $u):?>
                            <option value=<?=$u?>> <?=$u?> </option>
                            }
                        <?php endforeach;?>
                    </select>
                    </input>
                    <input class="btn" type="submit" name="addAccount" value="addAccount">
                </form>
            </div>
        </div>

        <div>
            <h2>Add new amount</h2>
            <div class="panel-amount">
                <form action="logic/action.php" method="POST">
                    <div class="column">
                        <label for="person2">Friend:</label>
                        <select name="person2">
                            <option selected="person2">Choose one</option>
                            <?php
                            foreach($users->getFriends($_SESSION['userId']) as $u):?>
                                <option value=<?= $u?>> <?= $u?> </option>
                                }
                            <?php endforeach;?>
                        </select>
                        <label for="deptType">Type:</label>
                        <select name="deptType" id="deptType">
                            <option value="dept">dept</option>
                            <option value="credit">credit</option>
                        </select>

                        <label for="amount">Amount:</label>
                        <input type="number" step="0.01" id="amount" name="amount" min="0" value="00.00">



                    <label for="description">Reason</label>
                    <input class="big" type="text" id="description" name="description">
                    </div>
                    <input class="btn"  type="submit" name="addAmount" value="addAmount">
                </form>
            </div>
        </div>
    </div>

    <h2>Accounts</h2>
    <div class="wrap-container">
        <?php foreach ($accounts->getAllAccountsWithUsername($_SESSION['name']) as $a):?>
            <?php $className = $accounts->getAccBalance($a,$_SESSION['userId']) < 0 ? 'bad-card' : 'good-card'; ?>
            <div class="panel account-card <?=$className?>">
                <div class="account-card-name">
                    <?php if($_SESSION['name']!=$users->getUsernameFromId($a['person1id'])): ?>
                        <h2> <?= $users->getUsernameFromId($a['person1id'])?> </h2>
                    <?php endif; ?>
                    <?php if($_SESSION['name']!=$users->getUsernameFromId($a['person2id'])): ?>
                        <h2> <?= $users->getUsernameFromId($a['person2id'])?> </h2>
                    <?php endif; ?>
                </div>
                <h2> <?=$accounts->getAccBalance($a,$_SESSION['userId'])?> </h2>
                <a href="account.php?account=<?=$a['id']?>"> <button class="btn">Details </button> </a>
            </div>
        <?php endforeach;?>
    </div>

</div>
</body>
</html>