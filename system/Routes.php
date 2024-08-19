<?php

class Routes
{
    /**
     * rota
     *
     * @param mixed $aParametro 
     * @return object
     */
    public static function rota($aParametro)
    {
        $aParGet    = (isset($aParametro['parametros']) ? $aParametro['parametros'] : "Home");
        $controller = "";
        $metodo     = "index";
        $acao       = "";
        $id         = null;
        $outrosPar  = [];
        $camControl = ".." . DIRECTORY_SEPARATOR . "App" . DIRECTORY_SEPARATOR . "Controller" . DIRECTORY_SEPARATOR;

        //http://velocityphp/
        
        // Usuario/formulario/update/10

        if (substr_count($aParGet, "/") > 0 ) {
            $aParam         = explode("/",$aParGet);
            $controller     = $aParam[0];
            $metodo         = $aParam[1];

            // Pega a ação a ser executada e o ID
            if (isset($aParam[2])) {

                $acao   = (isset($aParam[2]) ? $aParam[2] : "");

                if (in_array($aParam[2] , ['insert', 'update', 'delete', 'view'])) {
                    $id     = (isset($aParam[3]) ? $aParam[3] : 0);
                }
            }

            // Outros parâmetros
            if (isset($aParam[4])) {
                for ($rrr = 4; $rrr < count($aParam); $rrr++) {
                    $outrosPar[] = $aParam[$rrr];
                }
            }

        } else {
            $controller = $aParGet;
        }

        // Verifica se o controller existe
        if (!file_exists($camControl . $controller . ".php")) {
            $controller = "Erros";
            $metodo     = "controllerNotFound";
        }

        // Carregar o controller
        require_once $camControl . $controller . ".php";

        // Verificar se o método existe
        if (!method_exists($controller, $metodo)) {
            $controller = "Erros";
            $metodo     = "methodNotFound";
            // Carrega o controller Erros
            require_once $camControl . $controller . ".php";
        }
        
        // retornar os dados
        return new $controller([
            "controller"        => $controller,
            "metodo"            => $metodo,
            "acao"              => $acao,
            "id"                => $id,
            "OutrosParametros"  => $outrosPar,
            "model"             => $controller,
            "get"               => $_GET,
            "post"              => $_POST
        ]);
    }
}