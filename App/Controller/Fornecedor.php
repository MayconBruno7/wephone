<?php

use App\Library\ControllerMain;
use App\Library\Redirect;
use App\Library\Validator;
use App\Library\Session;

class Fornecedor extends ControllerMain
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

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $this->loadView("restrita/listaFornecedor", $this->model->lista("id"));
    }

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

        $EstadoModel = $this->loadModel("Estado");
        $dados['aEstado'] = $EstadoModel->lista('id');

        $CidadeModel = $this->loadModel("Cidade");
        $dados['aCidade'] = $CidadeModel->lista('id');

        return $this->loadView("restrita/formFornecedor", $dados);
    }

    /**
     * insert
     *
     * @return void
     */
    public function insert()
    {
        $post = $this->getPost();

        if (Validator::make($post, $this->model->validationRules)) {
            return Redirect::page("Fornecedor/form/insert");     // error
        } else {
           
            if ($this->model->insert([
                "nome" => $post['nome'],
                "cnpj" => preg_replace("/[^0-9]/", "", $post['cnpj']),
                "endereco" => $post['endereco'],
                "cidade" => $post['cidade'],
                "estado" => $post['estado'],
                "bairro" => $post['bairro'],
                "numero" => $post['numero'],
                "telefone" => preg_replace("/[^0-9]/", "", $post['telefone']),
                "statusRegistro" => $post['statusRegistro']
            ])) {
                Session::set("msgSuccess", "Fornecedor adicionada com sucesso.");
            } else {
                Session::set("msgError", "Falha tentar inserir uma nova Fornecedor.");
            }
    
            Redirect::page("Fornecedor");
        }
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
            return Redirect::page("Fornecedor/form/update/" . $post['id']);
        } else {

            if ($this->model->update(
                [
                    "id" => $post['id']
                ], 
                [
                    "nome" => $post['nome'],
                    "cnpj" => preg_replace("/[^0-9]/", "", $post['cnpj']),
                    "endereco" => $post['endereco'],
                    "cidade" => $post['cidade'],
                    "estado" => $post['estado'],
                    "bairro" => $post['bairro'],
                    "numero" => $post['numero'],
                    "telefone" => preg_replace("/[^0-9]/", "", $post['telefone']),
                    "statusRegistro" => $post['statusRegistro']
                ]
            )) {
                Session::set("msgSuccess", "Fornecedor alterada com sucesso.");
            } else {
                Session::set("msgError", "Falha tentar alterar a Fornecedor.");
            }

            return Redirect::page("Fornecedor");
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
            Session::set("msgSuccess", "Fornecedor excluída com sucesso.");
        } else {
            Session::set("msgError", "Falha tentar excluir a Fornecedor.");
        }

        Redirect::page("Fornecedor");
    }

    /**
     * requireAPI
     *
     * @return void
     */
    public function requireAPI()
    {
        $cnpj = $this->getOutrosParametros(2);

        if ($cnpj) {
            $data = $this->model->requireAPI($cnpj);
            header('Content-Type: application/json');
            echo json_encode($data);
        } else {
            echo json_encode(['error' => 'Parâmetro CNPJ não fornecido na requisição.']);
        }
    }

    /**
     * getCidadeComboBox
     *
     * @return string
    */
    public function getCidadeComboBox()
    {
        $dados = $this->model->getCidadeComboBox($this->getId());

        if (count($dados) == 0) {
            $dados[] = [
                "id" => ""
            ];
        }

        echo json_encode($dados);
    }
}