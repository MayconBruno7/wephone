<?php

    // Carregamentos
    require_once ".." . DIRECTORY_SEPARATOR . "App" . DIRECTORY_SEPARATOR . "Config" . DIRECTORY_SEPARATOR . "Constants.php";
    require_once ".." . DS . "system" . DS . "Ambiente.php";
    require_once ".." . DS . "system" . DS . "Routes.php";
    require_once ".." . DS . "system" . DS . "Autoload.php";

    // time zone
    date_default_timezone_set('America/Sao_Paulo');

    // Carregamento das variáveis de ambiente
    $amb = new Ambiente();
    $amb->Load();