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

    private $inputPassword = null;

    private $inputPasswordVerify = null;

    private $inputEmail = null;

    public $errors = null;

    public $state = null;

    public function __construct($loginFromView, $passwordFromView, $passwordVerifyFromView, $emailFromView)
    {
            $this->inputLogin = self::inputValidate($loginFromView);
            $this->inputPassword = self::inputValidate($passwordFromView);
            $this->inputPasswordVerify = self::inputValidate($passwordVerifyFromView);
            $this->inputEmail = self::inputValidate($emailFromView);
    }

    static function inputValidate($text) {
     $text = trim($text);
     $text = substr($text,0,29);
     $text = stripslashes($text);
     $text = htmlspecialchars($text);
     return $text;
    }

    function HasErrors($typeOfError){
        if(isset($this->errors["${typeOfError}"])){
            return true;
        } else {
            return false;
        }
    }

    function AlreadyExistCheck($comparable_value) : Object {
        if($this->inputLogin == $comparable_value && $comparable_value != null)
            $this->errors = ["login_errors" => self::ERROR_TYPES['login_errors']['already_exist']];
            $this->HasErrors();
            return $this;
    }

    function EmptyCheck() : void {
        if(empty($this->inputLogin) && !$this->HasErrors("login_errors"))
            $this->errors["login_errors"] = self::ERROR_TYPES["login_errors"]["empty"];
            $this->setState("fail");

        if(empty($this->inputPassword) && !$this->HasErrors("password_errors"))
            $this->errors["password_errors"] = self::ERROR_TYPES["password_errors"]["empty"];
            $this->setState("fail");

        if(empty($this->inputPasswordVerify) && !$this->HasErrors("password_verify_errors"))
            $this->errors["password_verify_errors"] = self::ERROR_TYPES["password_verify_errors"]["empty"];
            $this->setState("fail");

        if(empty($this->inputEmail) && !$this->HasErrors("email_errors"))
            $this->errors["email_errors"] = self::ERROR_TYPES["email_errors"]["empty"];
            $this->setState("fail");
    }

    function LengthCheck($min_length, $max_length) : Object {
        if(!empty($this->inputLogin) && $this->HasErrors()) {
            if (mb_strlen($this->inputLogin, "UTF-8") > $max_length || mb_strlen($this->inputLogin, "UTF-8") < $min_length)
                $this->errors = ["login_errors" => self::ERROR_TYPES['login_errors']['more_than_thirty_symbols or less_than_three_symbols']];
            $this->HasErrors();
            return $this;
        } else {
            return $this;
        }
    }

    function CorrectnessOfSymbols(){
        if(!ctype_alnum($this->inputLogin) && $this->HasErrors()) {
            $this->errors = ["login_errors" => self::ERROR_TYPES['login_errors']['incorrect_type_of_chars']];
            $this->HasErrors();
            return $this;
        } else {
            return $this;
        }
    }


    /**
     * @param null $state
     */
    public function setState($state): void
    {
        $this->state = $state;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }


}


