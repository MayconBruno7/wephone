<?php

    session_start();

    // Carregar as configurações do ambiente, bibliotecas de rotas, constants, etc...
    require_once ".." . DIRECTORY_SEPARATOR . "App" . DIRECTORY_SEPARATOR . "Config" . DIRECTORY_SEPARATOR . "Config.php";

    // Instanciando a classe Autoload
    $Autoload = new Autoload();

    // registrando o autoload com o spl
    spl_autoload_register([$Autoload, "library"]);

    // Criando o Controller
    $myController = Routes::rota($_GET);

    // Monta variável com o nome do método a ser executado
    $metodo = $myController->dados['metodo'];
    
    // chama o método do controller a ser executado
    $myController->$metodo();