<?php

    define("ROOT", __DIR__);
    require_once ROOT."/app/config.php";

    $route_name = (isset($_GET["route"])) ? $_GET["route"] : "/";

    switch($route_name) {
        case "/":
        case "termekek":
            $pageController->main_page();
            break;
        case "termek":
            $pageController->product_page();
            break;
        case "regisztracio":
            $pageController->registrate();
            break;

        case "api/termek-lista":
            $apiController->list_products();
            break;
        default:
            $pageController->not_found();
    }


?>