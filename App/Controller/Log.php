<?php

use App\Library\ControllerMain;
use App\Library\Redirect;
use App\Library\Validator;
use App\Library\Session;

class Log extends ControllerMain
{
    /**
     * construct
     *
     * @param array $dados  
     */
    public function __construct($dados)
    {
        $this->auxiliarConstruct($dados);

        // Somente pode ser acessado por usuários adminsitradores
        if (!$this->getAdministrador()) {
            return Redirect::page("Home");
        }
    }

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $dados = [];

        $FuncionarioModel = $this->loadModel('Funcionario');
        $dados['aFuncionario'] = $FuncionarioModel->lista('id');

        $UsuarioModel = $this->loadModel('Usuario');
        $dados['aUsuario'] = $UsuarioModel->lista('id');

        $dados['aLog'] = $this->model->lista("id");

        $this->loadView("restrita/log", $dados);
    }

    /**
     * viewLog
     *
     * @return void
     */
    public function viewLog()
    {
        $dados = [];
    
        $FuncionarioModel = $this->loadModel('Funcionario');
        $dados['aFuncionario'] = $FuncionarioModel->lista('id');
    
        $UsuarioModel = $this->loadModel('Usuario');
        $dados['aUsuario'] = $UsuarioModel->lista('id');
    
        if ($this->getAcao() != "new") {
            $registro = $this->model->getById($this->getId());
            // Mescla os dados de $registro com os dados existentes em $dados
            $dados = array_merge($dados, $registro);
        }
    
        return $this->loadView("restrita/viewLog", $dados);
    }
}