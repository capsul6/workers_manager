<?php


class Validation
{

  const ERROR_TYPES = array("login_errors" =>
  array("empty" => "логін не може бути пустим",
        "more_than_thirty_symbols" => "логін не може бути довше 30 символів",
        "less_than_three_symbols" => "логін не може бути коротшим за 3 символи",
        "incorrect_type_of_chars" => "логін повинен складатися з букв та/або цифр",
        "already_exist" => "користувач з таким логіном вже існує"),

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

    private $inputValue;

    public $error;

    public $state = "success";

    public function __construct($input)
    {
            $this->inputValue = $this::inputValidate($input);
    }

    static function inputValidate($text) {
     $text = trim($text);
     $text = substr($text,0,29);
     $text = stripslashes($text);
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
        if($this->inputValue == $comparable_value)
            $this->error = ["login_errors" => self::ERROR_TYPES['login_errors']['already_exist']];
            $this->HasNoErrors();
            return $this;
    }

    function EmptyLoginCheck() : Object {
        if(empty($this->inputValue))
            $this->error = ["login_errors" => self::ERROR_TYPES['login_errors']['empty']];
            $this->HasNoErrors();
            return $this;
    }


}