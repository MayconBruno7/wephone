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
    

    // /**
    //  * insert
    //  *
    //  * @return void
    //  */
    // public function insert()
    // {
    //     $post = $this->getPost();

    //     if (Validator::make($post, $this->model->validationRules)) {
    //         return Redirect::page("Cargo/form/insert");     // error
    //     } else {

    //         if ($this->model->insert([
    //             "nome" => $post['nome'],
    //             "statusRegistro" => $post['statusRegistro']
    //         ])) {
    //             Session::set("msgSuccess", "Cargo adicionada com sucesso.");
    //         } else {
    //             Session::set("msgError", "Falha tentar inserir uma nova Cargo.");
    //         }
    
    //         Redirect::page("Cargo");
    //     }
    // }

    // /**
    //  * update
    //  *
    //  * @return void
    //  */
    // public function update()
    // {
    //     $post = $this->getPost();

    //     if (Validator::make($post, $this->model->validationRules)) {
    //         // error
    //         return Redirect::page("Cargo/form/update/" . $post['id']);
    //     } else {

    //         if ($this->model->update(
    //             [
    //                 "id" => $post['id']
    //             ], 
    //             [
    //                 "nome" => $post['nome'],
    //                 "statusRegistro" => $post['statusRegistro']
    //             ]
    //         )) {
    //             Session::set("msgSuccess", "Cargo alterada com sucesso.");
    //         } else {
    //             Session::set("msgError", "Falha tentar alterar a Cargo.");
    //         }

    //         return Redirect::page("Cargo");
    //     }
    // }
    // /**
    //  * delete
    //  *
    //  * @return void
    //  */
    // public function delete()
    // {
    //     if ($this->model->delete(["id" => $this->getPost('id')])) {
    //         Session::set("msgSuccess", "Cargo excluída com sucesso.");
    //     } else {
    //         Session::set("msgError", "Falha tentar excluir a Cargo.");
    //     }

    //     Redirect::page("Cargo");
    // }
}