<?php

use App\Library\ControllerMain;
use App\Library\Session;
use App\Library\Redirect;
use App\Library\Validator;

class Usuario extends ControllerMain
{
    /**
     * lista
     *
     * @return void
     */
    public function index()
    {
        // Somente pode ser acessado por usuários adminsitradores
        if (!$this->getAdministrador()) {
            return Redirect::page("Home");
        }

        $this->loadView("usuario/listaUsuario", $this->model->getLista());
    }

    /**
     * form
     *
     * @return void
     */
    public function form()
    {
        $dados = [];

        if ( $this->getAcao() != 'new') {
            // buscar o usuário pelo $id no banco de dados
            $dados = $this->model->getById($this->getId());
        }

        $FuncionarioModel = $this->loadModel("Funcionario");
        $dados['aFuncionario'] = $FuncionarioModel->lista();

        return $this->loadView('usuario/formUsuario', $dados);
    }

    public function profile()
    {
        $dados = [];

        if ( $this->getAcao() != 'new') {
            // buscar o usuário pelo $id no banco de dados
            $dados = $this->model->getById($this->getId());
        }

        $FuncionarioModel = $this->loadModel("Funcionario");
        $dados['aFuncionario'] = $FuncionarioModel->recuperaFuncionario(Session::get('id_funcionario'));

        $CargoModel = $this->loadModel("Cargo");
        $dados['aCargo'] = $CargoModel->lista();

        return $this->loadView('usuario/profile', $dados);
    }

    /**
     * new - insere um novo usuário
     *
     * @return void
     */
    public function insert()
    {
        $post = $this->getPost();

        // Valida dados recebidos do formulário
        if (Validator::make($post, $this->model->validationRules)) {
            return Redirect::page("Usuario/form/insert");
        } else {

            if ($this->model->insert([
                "statusRegistro"    => $post['statusRegistro'],
                "nivel"             => $post['nivel'],
                "nome"              => $post['nome'],
                "email"             => $post['email'],
                "senha"             => password_hash($post['senha'], PASSWORD_DEFAULT),
                "id_funcionario"    => $post['funcionarios']
            ])) {
                return Redirect::page("Usuario", ["msgSuccess" => "Usuário inserido com sucesso !"]);
            } else {
                return Redirect::page("Usuario", ["msgError" => "Falha na inserção dos dados do Usuário !"]);
            }
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

        // Valida dados recebidos do formulário
        if (Validator::make($post, $this->model->validationRules)) {
            return Redirect::page("Usuario/form/update");
        } else {

            if ($this->model->update(['id' => $post['id']], [
                "nome"              => $post['nome'],
                "statusRegistro"    => $post['statusRegistro'],
                "nivel"             => $post['nivel'],
                "email"             => $post['email'],
                "id_funcionario"    => $post['funcionarios']
            ])) {
                return Redirect::page("Usuario", ["msgSuccess" => "Usuário alterado com sucesso !"]);
            } else {
                return Redirect::page("Usuario", ["msgError" => "Falha na alteração dos dados do Usuário !"]);
            } 
        }    
    }

    /**
     * delete -   Exclui um usuário no banco de dados
     *
     * @return void
     */
    public function delete()
    {
        $post = $this->getPost();

        if ($this->model->delete([ "id" => $post['id']])) {
            return Redirect::page("Usuario", ["msgSuccess" => "Usuário excluído com sucesso !"]);
        } else {
            return Redirect::page("Usuario", ["msgError" => "Falha na exclusão do Usuário !"]);
        }   
    }

    /**
     * trocaSenha - Chama a view Trocar a senha
     *
     * @return void
     */
    public function trocaSenha()
    {
        $this->loadView("usuario/formTrocaSenha");
    }

    /**
     * atualizaSenha - Atualiza a senha do usuário
     *
     * @return void
     */
    public function atualizaTrocaSenha() 
    {
        $post = $this->getPost();
        $userAtual = $this->model->getById($post["id"]);

        if ($userAtual) {

            if (password_verify(trim($post["senhaAtual"]), $userAtual['senha'])) {

                if (trim($post["novaSenha"]) == trim($post["novaSenha2"])) {

                    $lUpdate = $this->model->update(['id' =>$post['id']], ['senha' => password_hash($post["novaSenha"], PASSWORD_DEFAULT)]);

                    if ($lUpdate) {
                        return Redirect::page("Usuario/trocaSenha", [
                            "msgSuccess"    => "Senha alterada com sucesso !"
                        ]);  
                    } else {
                        return Redirect::page("Usuario/trocaSenha", [
                            "msgError"    => "Falha na atualização da nova senha !"
                        ]);    
                    }

                } else {
                    return Redirect::page("Usuario/trocaSenha", [
                        "msgError"    => "Nova senha e conferência da senha estão divergentes !"
                    ]);                  
                }

            } else {
                return Redirect::page("Usuario/trocaSenha", [
                    "msgError"    => "Senha atual informada não confere!"
                ]);               
            }
        } else {
            return Redirect::page("Usuario/trocaSenha", [
                "msgError"    => "Usuário inválido !"
            ]);   
        }
    }

    /**
     * perfil
     *
     * @return void
     */
    public function perfil()
    {
        $this->loadHelper("formulario");
        $this->loadView("admin/formPerfil", $this->model->getById(Session::get('userCodigo')));
    }

    /**
     * atualizaPerfil
     *
     * @return void
     */
    public function atualizaPerfil()
    {
        $post = $this->getPost();

        if ($this->model->update($post['id'], ['nome' => $post['nome'], 'email' => $post['email']])) {

            Session::set("usuarioLogin", $post['nome']);
            Session::set("usuarioEmail", $post['email']);

            return Redirect::page("Usuario/perfil", [
                "msgSuccess"    => "Perfil atualizado com sucesso!"
            ]);  

        } else {
            return Redirect::page("Usuario/perfil", [
                "msgError"    => "Falha na atualização do se perfil, favor tentar novamente mais tarde!"
            ]);  
        }
    }
}