<?php

use App\Library\ControllerMain;
use App\Library\Redirect;
use App\Library\Validator;
use App\Library\Session;

class HistoricoProduto extends ControllerMain
{
    /**
     * construct
     *
     * @param array $dados 
     */
    public function __construct($dados)
    {
        $this->auxiliarConstruct($dados);

        // Só acessa se tiver logado
        if (!$this->getUsuario()) {
            return Redirect::page("Home");
        }

    }

    // /**
    //  * index
    //  *
    //  * @return void
    //  */
    // public function index()
    // {
        
    //     $this->loadView("restrita/HistoricoProdutoMovimentacao", $this->model->historico_produto_movimentacao($this->getId()));
    // }

    public function getHistoricoProduto()
    {

        $dados = $this->model->getHistoricoProduto($this->getOutrosParametros(2)); 
    
        echo json_encode($dados);
    
    }
}