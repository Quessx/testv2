<?php
session_start();
require_once "class/Auth.php";

class Ajax extends Auth
{

    public $passValid = "/(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!@#$%^&*]{6,}/";
    public $emailValid = "/^[_A-Za-z0-9-\\+]+(\\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\\.[A-Za-z0-9]+)*(\\.[A-Za-z]{2,})$/";
    public $nameValid = "/(?=^.{2,}$)[a-zA-Z0-9]+$/";
    public $loginValid = "/(?=^.{6,}$)[a-zA-Z0-9]+$/";

    public function login($login, $password)
    {

        $login = strtolower($login);
        $error_fields = [];

        if ($login == '') {
            $error_fields[] = 'login';
        }

        if ($password == '') {
            $error_fields[] = 'password';
        }

        $auth_check = $this->authorize($login, $password);

        if ($auth_check) {
            $response = [
                "status" => true
            ];

            echo json_encode($response);

        } else {

            $response = [
                "status" => false,
                "type" => 1,
                "message" => "Неверный логин/пароль",
                "fields" => $error_fields
            ];
            echo json_encode($response);

            die();
        }

    }

    public function register($login, $email, $password, $passwordConf, $name)
    {

        $error_fields = [];
        $login = strtolower($login);

        if (empty($login) || preg_match($this->loginValid, $login) == 0) {
            $error_fields[] = 'login';
        }

        if (empty($password) || preg_match($this->passValid, $password) == 0) {
            $error_fields[] = 'password';
        }

        if (empty($passwordConf)) {
            $error_fields[] = 'password_confirm';
        }

        if ($password !== $passwordConf) {
            $error_fields[] = 'password';
            $error_fields[] = 'password_confirm';
        }

        if (empty($email) || preg_match($this->emailValid, $email) == 0) {
            $error_fields[] = 'email';

        }

        if (empty($name) || preg_match($this->nameValid, $name) == 0) {
            $error_fields[] = 'username';
        }

        if(!empty($error_fields)){
            $response = [
                "status" => false,
                "message" => "Неверные данные",
                "type" => 1,
                "fields" => $error_fields,
            ];

            echo json_encode($response);

            die();
        }

        $check = $this->create($login, $email, $password, $name);

        if($check) {
            $response = [
                "status" => true
            ];
            echo json_encode($response);

            die();
        }
        else {
            $error_fields[] = 'email';
            $error_fields[] = 'login';
            $response = [
                "status" => false,
                "message" => "Такой пользователь существует",
                "type" => 1,
                "fields" => $error_fields,
            ];
            echo json_encode($response);

            die();
        }

    }

}

$response = new Ajax();
if(!isset($_POST['email'])) {
    $response->login($_POST['login'], $_POST['password']);
}
if(isset($_POST['email'])){
    $response->register($_POST['login'], $_POST['email'], $_POST['password'], $_POST['password_confirm'], $_POST['username']);
}
