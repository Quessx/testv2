<?php

require_once "Database.php";

class Auth extends Database
{

    private $db;
    private $login;
    private $user_id;

    public $is_authorized = true;

    public function __construct()
    {
        $this->connectdb();
    }

    public function checkLogin(){
        if(isset($_COOKIE['hash']) && isset($_COOKIE['sid']) && isset($_COOKIE['id'])) {

            foreach ($this->db->from('users')->getList() as $k => $v) {
                $row = $v[$_COOKIE['id']];
            }

            if (($row['hash'] !== $_COOKIE['hash']) &&
                ($row['sid'] !== $_COOKIE['sid']) &&
                ($row['id'] !== $_COOKIE['id']) &&
                ($row['sid'] !== $_SESSION['user_id'])) {
                setcookie("sid", "");
                setcookie("hash", "");
                setcookie("id", "");
                header('Location: /');
                }
        }

        else {
            setcookie("sid", "");
            setcookie("hash", "");
            setcookie("id", "");

            header('Location: /');
        }
    }

    public function echoName(){
        foreach ($this->db->from('users')->getList() as $k => $v){
            $name = $v[$_COOKIE['id']-1]['name'];
        }
        return ucfirst($name);
    }



    public function passwordHash($password, $salt = null, $iterations = 10)
    {

        if (!$salt) {
            $salt = uniqid();
        }

        $hash = md5(md5($password . md5(sha1($salt))));

        for ($i = 0; $i < $iterations; $i++) {
            $hash = md5(md5(sha1($hash)));
        }

        return array('hash' => $hash, 'salt' => $salt);
    }

    public function getSalt($login)
    {
        $query = $this->db->from('users')->where('login', $login);

        $row = $query->where;
        $res = $row["salt"];

        return $res;
    }

    public function authorize($login, $password)
    {
        $salt = $this->getSalt($login);

        if (!$salt) {
            return false;
        }

        $hashes = $this->passwordHash($password, $salt);

        $query = $this->db->from('users')->where('login', $login)->where('password', $hashes['hash']);

        if (!$query->where) {
            return false;
        } else {

            $this->user_id = $query->where['id'];
            $_SESSION["user_id"] = $this->user_id;

            $sid = session_id();
            $hash = md5(md5($sid . md5(sha1(time()))));

            setcookie('sid', $sid, time() + 7 * 24 * 60 * 60, "/", "", false, true);
            setcookie('hash', $hash, time() + 7 * 24 * 60 * 60, "/", "", false, true);
            setcookie('id', $this->user_id, time() + 7 * 24 * 60 * 60, "/", "", false, true);

            $this->db->from('users')->where('login', $login)->where('password', $hashes['hash'])->update('hash', $hash);
            $this->db->from('users')->where('login', $login)->where('password', $hashes['hash'])->update('session', $sid);


        }

        return $this->is_authorized;
    }

    public function logout()
    {
        if (!empty($_COOKIE["sid"]) || !empty($_COOKIE['hash']) || !empty($_COOKIE['id'])) {
            header("Location: /main.php");
        }
        else {
            setcookie("sid", "");
            setcookie("hash", "");
            setcookie("id", "");
        }
    }

    public function create($login, $email, $password, $name)
    {
        $row = $this->db->from('users')->getList()['row'];

        foreach ($row as $key => $val) {
            if ($val['login'] == $login) {
                return false;
            } else if ($val['email'] == $email) {
                return false;
            }
        }

        $hashes = $this->passwordHash($password);
        $sid = session_id();
        $hash = md5(md5(sha1(time())));

        $this->db->from('users')->insert('id', '');
        $this->db->from('users')->insert('login', $login);
        $this->db->from('users')->insert('password', $hashes['hash']);
        $this->db->from('users')->insert('salt', $hashes['salt']);
        $this->db->from('users')->insert('email', $email);
        $this->db->from('users')->insert('name', $name);
        $this->db->from('users')->insert('hash', $hash);
        $this->db->from('users')->insert('session', $sid);

        return $this->is_authorized;
    }

    public function connectdb()
    {
        $this->db = new Database();
    }

}


