<?php


class Validation
{

  const ERROR_TYPES = array("login_errors" =>
  array("empty" => "логін не може бути пустим",
        "already_exist" => "користувач з таким логіном вже існує",
        "more_than_thirty_symbols or less_than_three_symbols" => "логін не може бути довшим 30 символів та коротшим 3",
        "incorrect_type_of_chars" => "логін повинен складатися з букв та/або цифр"),


        "password_errors" => array("empty" => "пароль не може бути пустим",
            "less_than_three_symbols" => "пароль не може бути коротшим за 3 символи",
            "more_than_thirty_symbols" => "пароль не може бути довше 30 символів",
            "incorrect_type_of_chars" => "пароль повинен складатися з букв та/або цифр"),

        "password_verify_errors" => array("empty" => "повторний пароль не може бути пустим",
            "don`t_found" => "повторний пароль не співпадає з основним"),

        "email_errors" => array("empty" => "емейл не може бути пустим",
            "don`t_contain_symbol" => "це не схоже на емейл адресу",
            "already_exist" => "користувач з таким емейлом вже існує")
    );

    private $inputLogin = null;

    public $error = null;

    public $state = null;

    public function __construct($input)
    {
            $this->inputLogin = $this::inputValidate($input);
    }

    static function inputValidate($text) {
     $text = trim($text);
     $text = substr($text,0,29);
     $text = stripslashes($text);
     $text = htmlspecialchars($text);
     return $text;
    }

    function HasNoErrors(){
        if(empty($this->error)){
            $this->state == "success";
            return true;
        } else {
            $this->state = "fail";
            return false;
        }

    }

    function AlreadyExistCheck($comparable_value) : Object {
        if($this->inputLogin == $comparable_value && $comparable_value != null)
            $this->error = ["login_errors" => self::ERROR_TYPES['login_errors']['already_exist']];
            $this->HasNoErrors();
            return $this;
    }

    function EmptyLoginCheck() : Object {
        if(empty($this->inputLogin) && $this->HasNoErrors()) {
            $this->error = ["login_errors" => self::ERROR_TYPES['login_errors']['empty']];
            $this->HasNoErrors();
            return $this;
        } else {
            return $this;
        }
    }

    function LengthCheck() : Object {
        if(!empty($this->inputLogin) && $this->HasNoErrors()) {
            if (mb_strlen($this->inputLogin, "UTF-8") > 30 || mb_strlen($this->inputLogin, "UTF-8") < 3)
                $this->error = ["login_errors" => self::ERROR_TYPES['login_errors']['more_than_thirty_symbols or less_than_three_symbols']];
            $this->HasNoErrors();
            return $this;
        } else {
            return $this;
        }
    }

    function CorrectnessOfSymbols(){
        if(!ctype_alnum($this->inputLogin) && $this->HasNoErrors()) {
            $this->error = ["login_errors" => self::ERROR_TYPES['login_errors']['incorrect_type_of_chars']];
            $this->HasNoErrors();
            return $this;
        } else {
            return $this;
        }
    }

}


