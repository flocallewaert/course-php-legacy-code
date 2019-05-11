<?php

class Validator
{
    public $errors = [];

    public function __construct (
        $config,
        $data
    ) {

        if (
            count($data) != count($config["data"])
        ) {
            die("Tentative : faille XSS");
        }


        foreach (
            $config["data"] as $name => $info
        ) {
            if (
                !isset($data[$name])
            ) {
                die("Tentative : faille XSS");
            } else {

                if (
                    ($info["required"] ?? false)
                    AND !self::notEmpty($data[$name])
                ) {
                    $this->errors[]= $info["error"];
                }

                if (
                    isset($info["minlength"])
                    AND !self::minLength($data[$name], $info["minlength"])
                ) {
                    $this->errors[]= $info["error"];
                }

                if (
                    isset($info["maxlength"])
                    AND !self::maxLength($data[$name], $info["maxlength"])
                ) {
                    $this->errors[]= $info["error"];
                }

                if (
                    $info["type"] === "email"
                    AND !self::checkEmail($data[$name])
                ) {
                    $this->errors[]= $info["error"];
                }

                if (
                    isset($info["confirm"])
                    AND $data[$name] !== $data[$info["confirm"]]
                ) {
                    $this->errors[]= $info["error"];
                } else if (
                    $info["type"] === "password"
                    AND !self::checkPassword($data[$name])
                ) {
                    $this->errors[]= $info["error"];
                }
            }

        }
    }

    public static function notEmpty(
        $string
    ) {
        return !empty(trim($string));
    } 

    public static function minLength(
        $string,
        $length
    ) {
        return strlen(trim($string)) >= $length;
    } 

    public static function maxLength(
        $string,
        $length
    ) {
        return strlen(trim($string)) <= $length;
    }

    public static function checkEmail(
        $string
    ) {
        return filter_var(trim($string), FILTER_VALIDATE_EMAIL);
    }

    public static function checkPassword(
        $string
    ) {
        return (
            preg_match("#[a-z]#", $string)
            AND preg_match("#[A-Z]#", $string)
            AND preg_match("#[0-9]#", $string)
        );
    }
}


