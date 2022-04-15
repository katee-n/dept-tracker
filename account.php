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
    <link rel="stylesheet" href="style/dashboard.css">
</head>
<body>

<?php
$accounts= new Accounts();
$users = new User();
$accountEntry = new AccountEntry();
$a = $accounts->getAccount($_GET['account']);
?>

<div class="content">



<div class="header">
    <div class="div-logo">
        <img src="logo.png" class="logo-img" alt="logo">
        <h3>I owe you</h3>
    </div>
    <div class="greeting">
        <h1>Your account overview with
            <?php if($_SESSION['userId']!=$a['person1id']):?>
                <?=$users->getUsernameFromId($a['person1id'])?></h1>
            <?php else:?>
                <?=$users->getUsernameFromId($a['person2id'])?></h1>
            <?php endif;?>
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

    <form action="logic/action.php" method="POST">
        <input class="btn" type="submit" class="button" name="Back" value="back" />
    </form>

    <h2>Overview</h2>
    <div class="row">
        <?php $disabled = $_SESSION['name']!=$users->getUsernameFromId($a['person1id'])? 'disabled' : ' '; ?>
        <?php $dept = $_SESSION['name']!=$users->getUsernameFromId($a['person1id'])? $a['dept2'] : $a['dept1']?>
        <?php $credit = $_SESSION['name']!=$users->getUsernameFromId($a['person1id'])? $a['dept1'] : $a['dept2']?>
            <div class="panel overview-card good-card">
                <h3>Credits:</h3>
                <p><?=$credit?> €</p>
            </div>
            <div class="panel overview-card bad-card">
                <h3>Depts:</h3>
                <p><?=$dept?> €</p>
            </div>
            <div class="panel overview-card">
                <h3>Balance:</h3>
                <p><?=$accounts->getAccBalance($a,$_SESSION['userId']);?> €</p>
            </div>
    </div>

    <h2>Add Amount</h2>
    <div class="panel">
        <form action="logic/action.php" id="addAmount" method="POST">
            <input type="hidden" id="custId" name="accountId" value="<?=$a['id']?>">
            <div class="column">
                <label for="deptType">Type:</label>
                <select name="deptType" id="deptType">
                    <option value="dept">dept</option>
                    <option value="credit">credit</option>
                </select>
                <label for="amount">Amount:</label>
                <input type="number" step="0.01" id="amount" name="amount" min="0" value="00.00">
            </div>
            <label for="description">Reason</label>
            <input type="text" id="description" name="description">
            <input class="btn"  type="submit"  name="addAmountEntry" value="addAmount">
        </form>
    </div>

    <h2>Account Entries</h2>
<?php foreach ($accountEntry->getAllEntrysForAccount($a['id']) as $e):?>
    <?php $className = $e['creditorId']==$_SESSION['userId'] ? 'good-card' : 'bad-card'; ?>
    <?php $disabled = $e['paid']==1 ? 'disabled' : ' '; ?>
    <div class="panel <?=$className?> <?=$disabled?> account-entry">
        <h2> <?=$e['amount']?> €</h2>
        <p> <?=$e['description']?> </p>
        <form action="logic/action.php" method="POST">
            <input type="hidden" id="entryId" name="entryId" value="<?=$e['id']?>">
            <input type="submit" class="btn" name="cleared" value="cleared" />
        </form>
    </div>
<?php endforeach;?>

</div>

</body>
</html>

