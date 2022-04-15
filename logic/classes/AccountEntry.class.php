<?php

spl_autoload_register(function ($class){
    require_once("Accounts.class.php");
});
class AccountEntry
{
    private $db;
    private $accounts;

    function __construct(){
        try{
            $this->db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER,DB_PW);
        }catch(PDOException $e){
            echo $e;
        }
        $this->accounts = new Accounts();
    }

    public function getAllEntrysForAccount($accountId){
        $stmt = $this->db->prepare("SELECT * FROM accountEntry where accountId = $accountId");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function addNewEntry($accountId, $p1Id, $p2Id, $amount,$deptType, $description){

        $stmt = $this->db->prepare("INSERT INTO accountEntry (accountId,amount,creditorId,debitorId,description) VALUES (:id,:amount,:crId,:deId,:descr)");
        $stmt->bindValue(":id",$accountId);
        $stmt->bindValue(":amount",$amount);
        $stmt->bindValue(":descr",$description);

        if($deptType=="credit"){
            $stmt->bindValue(":crId",$p1Id);
            $stmt->bindValue(":deId",$p2Id);
            $this->accounts->updateAccount($accountId,$p2Id,$amount);
        }else{
            $stmt->bindValue(":crId",$p2Id);
            $stmt->bindValue(":deId",$p1Id);
            $this->accounts->updateAccount($accountId,$p1Id,$amount);
        }

        $stmt->execute();
    }

    public function clearEntry($e){
        $id = $e['id'];
        $stmt = $this->db->prepare("UPDATE accountEntry SET paid = true WHERE id = $id");
        $stmt->execute();
    }

    public function getEntry($entryId)
    {
        $stmt = $this->db->prepare("SELECT * FROM accountEntry where id = $entryId");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result[0];
    }
}