<?php

use App\Library\ControllerMain;
use App\Library\Redirect;
use App\Library\Validator;
use App\Library\Session;

class MovimentacaoItem extends ControllerMain
{

    /**
     * form
     *
     * @return void
     */
    public function form()
    {
        $dados = [];

        if ($this->getAcao() != "insert") {
            $dados = $this->model->getById($this->getId());
        }

        $MovimentacaoItemModel = $this->loadModel("MovimentacaoItem");
        $dados['aItemMovimentacao'] = $MovimentacaoItemModel->listaProdutos($this->getId());

        $FornecedorModel = $this->loadModel("Fornecedor");
        $dados['aFornecedorMovimentacao'] = $FornecedorModel->lista('id');

        return $this->loadView("restrita/formMovimentacao", $dados);
    }

    /**
     * update
     *
     * @return void
     */
    public function update()
    {
        $post = $this->getPost();

        if (Validator::make($post, $this->model->validationRules)) {
            // error
            return Redirect::page("Movimentacao/form/update/" . $post['id']);
        } else {

            if ($this->model->update(
                [
                    "id" => $post['id']
                ], 
                [
                    "nome" => $post['nome'],
                    "statusRegistro" => $post['statusRegistro']
                ]
            )) {
                Session::set("msgSuccess", "Movimentacao alterada com sucesso.");
            } else {
                Session::set("msgError", "Falha tentar alterar a Movimentacao.");
            }

            return Redirect::page("Movimentacao");
        }
    }
    /**
     * delete
     *
     * @return void
     */
    public function delete()
    {
        if ($this->model->delete(["id" => $this->getPost('id')])) {
            Session::set("msgSuccess", "Movimentacao excluída com sucesso.");
        } else {
            Session::set("msgError", "Falha tentar excluir a Movimentacao.");
        }

        Redirect::page("Movimentacao");
    }
}