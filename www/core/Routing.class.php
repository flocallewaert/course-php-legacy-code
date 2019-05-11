<?php

class Routing
{
    public static $routeFile = "routes.yml";

    public static function getRoute (
        $slug
    ) {
        $routes = yaml_parse_file(self::$routeFile);

        if (
            isset($routes[$slug])
        ) {
            if (
                empty($routes[$slug]["controller"])
                OR empty($routes[$slug]["action"])
            ) {
                die("Il y a une erreur dans le fichier routes.yml");
            }

            $c = ucfirst($routes[$slug]["controller"])."Controller";
            $a = $routes[$slug]["action"]."Action";
            $cPath = "controllers/".$c.".class.php";
        } else {
            return [
                "c" => null,
                "a" => null,
                "cPath" => null,
            ];
        }

        return [
            "c" => $c,
            "a" => $a,
            "cPath" => $cPath,
        ];
    }


    public static function getSlug (
        $c,
        $a
    ) {
        $routes = yaml_parse_file(self::$routeFile);

        foreach (
            $routes as $slug => $cAndA
        ) {
            if (
                !empty($cAndA["controller"])
                AND !empty($cAndA["action"])
                AND $cAndA["controller"] === $c
                AND $cAndA["action"] === $a
            ) {
                return $slug;
            }
        }

        return null;
    }
}










