<?php

    define("ROOT", __DIR__);
    require_once ROOT."/app/config.php";

    $route_name = (isset($_GET["route"])) ? $_GET["route"] : "/";

    switch($route_name) {
        case "/":
        case "termekek":
            $pageController->main_page();
            break;

        case "api/termek-lista":
            $apiController->list_products();
            break;
        default:
            echo "404 Controller hívás";
    }


?>