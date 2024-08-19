<?php

use App\Library\ControllerMain;

class Erros extends ControllerMain
{
    /**
     * controllerNotFound
     *
     * @return void
     */
    public function controllerNotFound()
	{
        return $this->loadView("comuns/erros", ["msgError" => "Controller não localizado."]);
    }

    /**
     * methodNotFound
     *
     * @return void
     */
    public function methodNotFound()
    {
        return $this->loadView("comuns/erros", ["msgError" => "Método não localizado no controller"]);
    }
}