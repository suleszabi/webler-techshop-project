<?php

    # Database configuration
    $db_host = "localhost";
    $db_name = "techshop";
    $db_user = "root";
    $db_pwd = "";
    $db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pwd);
    $db->query("SET NAMES utf8");


    # Load Controllers
    require_once ROOT."/app/controllers/PageController.php";
    $pageController = new PageController($db);

?>