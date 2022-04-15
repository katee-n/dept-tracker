<?php

session_start();

require_once("functions.inc.php");

$accounts = new Accounts();
$users = new User();
$accountEntry = new AccountEntry();

if(isset($_POST['Login'])){
    $darfrein = $users->login($_POST['benutzername'],$_POST['passwort']);
    if($darfrein){
        $_SESSION['eingeloggt'] = true;
        $_SESSION['name'] = $_POST['benutzername'];
        $_SESSION['userId'] = $users->getIdFromUsername($_POST['benutzername']);
        header("Location: ../dashboard.php");

    }else{
        unset($_SESSION['eingeloggt']);
        header("Location: ../index.php?error=1");
    }
}

if(isset($_POST['Register'])){
    $darfrein = $users->register($_POST['benutzername'],$_POST['passwort']);
    if($darfrein){
        $_SESSION['eingeloggt'] = true;
        $_SESSION['name'] = $_POST['benutzername'];
        $_SESSION['userId'] = $users->getIdFromUsername($_POST['benutzername']);
        header("Location: ../dashboard.php");
    }else{
        unset($_SESSION['eingeloggt']);
        header("Location: ../register.php?error=1");
    }
}

if(isset($_POST['Logout'])){
    session_start();
    session_destroy();
    header("Location: ../index.php");
}

if(isset($_POST['addAccount'])){
    $accounts->addAccount($_SESSION['name'],$_POST['person']);
    header("Location: ../dashboard.php");
}

if(isset($_POST['addAmount'])){

    $p1Id = $users->getIdFromUsername($_SESSION['name']);
    $p2Id = $users->getIdFromUsername($_POST['person2']);
    $accountId = $accounts->getAccountByNames($_SESSION['name'],$_POST['person2']);
    $accountEntry->addNewEntry($accountId,$p1Id,$p2Id,$_POST['amount'],$_POST['deptType'],$_POST['description']);
    header("Location: ../account.php?account=$accountId");
}
if(isset($_POST['addAmountEntry'])){
    $accountId = $_POST['accountId'];
    $p1Id = $_SESSION['userId'];
    $p2Id = $users->getIdFromUsername($users->getOtherUser($_SESSION['userId'],$accounts->getAccount($_POST['accountId'])));
    $accountEntry->addNewEntry($accountId,$p1Id,$p2Id,$_POST['amount'],$_POST['deptType'],$_POST['description']);
    header("Location: ../account.php?account=$accountId");
}

if(isset($_POST['Back'])){
    header("Location: ../dashboard.php");
}

if(isset($_POST['cleared'])){
    $p1Id = $_SESSION['userId'];
    $e = $accountEntry->getEntry($_POST['entryId']);
    $accountId = $e['accountId'];
    if($e['paid']!=1){
        $a = $accounts->getAccount($accountId);
        $p2Id = $users->getIdFromUsername($users->getOtherUser($p1Id,$a));
        $accountEntry->clearEntry($e);
        $negAmount = $e['amount']*(-1);
        $accounts->updateAccount($e['accountId'],$e['debitorId'],$negAmount);
    }
    header("Location: ../account.php?account=$accountId");
}