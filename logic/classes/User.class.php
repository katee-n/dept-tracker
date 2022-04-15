<?php
define("DB_HOST","mysql5");
define("DB_NAME","db_mt201019_1");
define("DB_USER","mt201019");
define("DB_PW","H3Bp6psBbNuY");


spl_autoload_register(function ($class){
    require_once ("Accounts.class.php");
});

class User
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

    public function getAllUsers(){
        $stmt = $this->db->prepare("SELECT * FROM user");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function createUser($benutzer_eingabe, $passwort_eingabe){
        $hash_pwd = $this->createHashPwd($passwort_eingabe);
        $stmt = $this->db->prepare("INSERT INTO `user` (`username`, `passwordHash`) VALUES (:benutzer_eingabe,:hash_pwd)");
        $stmt->bindValue(":benutzer_eingabe",$benutzer_eingabe);
        $stmt->bindValue(":hash_pwd",$hash_pwd);
        $stmt->execute();
    }

    private function createHashPwd($password){
        $schluessel = "1gh3X5";
        $md5 = md5($password);
        $sha1 = sha1($password);
        return $md5 . $schluessel . $sha1;
    }

    public function login($benutzer_eingabe, $passwort_eingabe){
        $inputHash = $this->createHashPwd($passwort_eingabe);
        $stmt = $this->db->prepare("SELECT * FROM `user` WHERE username like '$benutzer_eingabe' and passwordHash like '$inputHash'");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(count($result[0])==0){
            return false;
        }
        return true;
    }
    function register($benutzer_eingabe,$passwort_eingabe){
        foreach ($this->getAllUsers() as $u) {
            if ($u['username'] == $benutzer_eingabe) {
                return false;
            }
        }
        $this->createUser($benutzer_eingabe,$passwort_eingabe);
        return true;
    }

    public function getUsernameFromId($id){
        $stmt = $this->db->prepare("SELECT username FROM `user` WHERE id = $id");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result[0]['username'];
    }

    public function getIdFromUsername($username){
        $stmt = $this->db->prepare("SELECT id FROM `user` WHERE username like '$username'");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result[0]['id'];
    }

    public function getFriends($userId)
    {
        $friends = array();

        $myaccounts = $this->accounts->getAllAccountsWithUsername($this->getUsernameFromId($userId));
        foreach ($myaccounts as $mya){
            $otherUser = $this->getOtherUser($userId, $mya);
            array_push($friends,$otherUser);
        }
        return $friends;
    }

    public function getOtherUser($userId, $account)
    {
        if($account['person1id']==$userId){
            return $this->getUsernameFromId($account['person2id']);
        }else if($account['person2id']==$userId){
            return $this->getUsernameFromId($account['person1id']);
        }
    }

    public function getNewFriends($userId)
    {
        $users = $this->getAllUsers();
        $nofriends = array();
        foreach ($users as $user){
            if(!$this->accounts->sharedAccountTrue($userId, $user['id']) && $user['id']!=$userId){
                array_push($nofriends,$this->getUsernameFromId($user['id']));
            }
        }
        return $nofriends;
    }

}