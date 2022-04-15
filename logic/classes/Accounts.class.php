<?php
define("DB_HOST","mysql5");
define("DB_NAME","db_mt201019_1");
define("DB_USER","mt201019");
define("DB_PW","H3Bp6psBbNuY");

spl_autoload_register(function ($class){
    require_once ("User.class.php");
});

class Accounts
{
    private $db;

    function __construct(){
        try{
            $this->db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER,DB_PW);
        }catch(PDOException $e){
            echo $e;
        }
    }

    public function getAllAccounts(){
        $stmt = $this->db->prepare("SELECT * FROM accounts");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    public function getAllAccountsWithUsername($username){
        $user = new User();
        $id = $user->getIdFromUsername($username);
        $stmt = $this->db->prepare("SELECT * FROM accounts WHERE person1id = $id or person2id = $id");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getAccount($id){
        $stmt = $this->db->prepare("SELECT * FROM accounts WHERE id = $id");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result[0];
    }

    public function getBalance($username)
    {
        return $this->getIncome($username)-$this->getDepts($username);
    }

    public function getDepts($username)
    {
        $user = new User();
        $accounts = $this->getAllAccountsWithUsername($username);
        $depts = 0;
        foreach ($accounts as $a){
            if($user->getUsernameFromId($a['person1id']) ==$username ){
                $depts+= $a['dept1'];
            }else{
                $depts+= $a['dept2'];
            }
        }
        return $depts;
    }

    public function getIncome($username)
    {
        $user = new User();
        $accounts = $this->getAllAccountsWithUsername($username);
        $depts = 0;
        foreach ($accounts as $a){
            if($user->getUsernameFromId($a['person1id']) ==$username ){
                $depts+= $a['dept2'];
            }else{
                $depts+= $a['dept1'];
            }
        }
        return $depts;
    }

    public function addAccount($p1, $p2){
        $user = new User();
        $stmt = $this->db->prepare("INSERT INTO accounts (person1id,person2id,dept1,dept2) VALUES (:p1,:p2,0,0)");
        $stmt->bindValue(":p1",$user->getIdFromUsername($p1));
        $stmt->bindValue(":p2",$user->getIdFromUsername($p2));
        $stmt->execute();
    }

    public function sharedAccountTrue($userId, $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM accounts WHERE person1id = $userId and person2id = $id");
        $stmt->execute();
        $result= $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(count($result)!=0){
            return true;
        }
        $stmt = $this->db->prepare("SELECT * FROM accounts WHERE person1id = $id and person2id = $userId");
        $stmt->execute();
        $result2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(count($result2)!=0){
            return true;
        }
        return false;

    }

    public function getAccBalance($a,$userId)
    {
        if($userId==$a['person1id']){
            return $a['dept2']-$a['dept1'];
        }else{
            return $a['dept1']-$a['dept2'];
        }
    }

    public function getAccountByNames( $p1, $p2)
    {
        $users = new User();
        $accounts = $this->getAllAccountsWithUsername($p1);
        foreach ($accounts as $a){
            if($a['person1id']==$users->getIdFromUsername($p2) || $a['person2id']==$users->getIdFromUsername($p2) ){
                return $a['id'];
            }
        }
    }

    public function updateAccount($accountId, $p2Id, $amount)
    {
        $stmt = $this->db->prepare("UPDATE accounts SET dept1 = dept1+$amount WHERE id = $accountId and person1id = $p2Id");
        $stmt->execute();
        $stmt = $this->db->prepare("UPDATE accounts SET dept2 = dept2+$amount WHERE id = $accountId and person2id = $p2Id");
        $stmt->execute();
    }


}