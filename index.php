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
        case "bejelentkezes":
            $pageController->login();
            break;
        case "kijelentkezes":
            $pageController->logout();
            break;
        case "kosar":
            $pageController->cart();
            break;
        case "kosarmuvelet":
            $pageController->cartMethod();
            break;
        case "rendeles":
            $pageController->order();
            break;
        
        // Admin route-ok
        case "termek-torles":
            $pageController->deleteProduct();
            break;
        case "uj-termek":
            $pageController->newProduct();
            break;

        // API route-ok
        case "api/termek-lista":
            $apiController->list_products();
            break;
        default:
            $pageController->not_found();
    }


?>