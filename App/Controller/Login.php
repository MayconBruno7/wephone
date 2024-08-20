<?php

use App\Library\ControllerMain;
use App\Library\Redirect;
use App\Library\Session;
use App\Library\Email;
use App\Library\Validator;
use App\Library\ModelMain;


use App\Library\Formulario;

class Login extends ControllerMain 
{
    public function signIn()
    {
        $UsuarioModel = $this->loadModel("Usuario");
        $post = $this->getPost();

        // super usário
        $superUser = $UsuarioModel->criaSuperUser();

        if ($superUser > 0) {          // 1=Falhou criação do super user; 2=sucesso na criação do super user
            return Redirect::page("home/login");
        }

        // Buscar usuário na base de dados

        $aUsuario = $UsuarioModel->getUserEmail($post['email']);

        if (count($aUsuario) > 0 ) {

            // validar a senha            
            if (!password_verify(trim($post["senha"]), $aUsuario['senha']) ) {
                Session::set("msgError", 'Usuário e ou senha inválido.');
                return Redirect::page("home/login");
            }
            
            // validar o status do usuário            
            if ($aUsuario['statusRegistro'] == 2 ) {
                Session::set("msgError", "Usuário Inativo, não será possível prosseguir !");
                return Redirect::page("home/login");
            }

            //  Criar flag's de usuário logado no sistema
            
            Session::set("usuarioId"   , $aUsuario['id']);
            Session::set("usuarioLogin", $aUsuario['nome']);
            Session::set("usuarioEmail", $aUsuario['email']);
            Session::set("usuarioNivel", $aUsuario['nivel']);
            Session::set("usuarioSenha", $aUsuario['senha']);
            Session::set("id_funcionario", $aUsuario['id_funcionario']);

            $FuncionarioModel = $this->loadModel("Funcionario");
            $dados['aFuncionario'] = $FuncionarioModel->recuperaFuncionario($aUsuario['id_funcionario']);

            Session::set("usuarioImagem", $dados['aFuncionario'][0]['imagem']);

            // Cookies
            if (isset($_COOKIE['username']) && $_COOKIE['username']  != $post["email"]) {
                // Limpar cookies
                setcookie('username', '', time() - 3600, "/");
                setcookie('password', '', time() - 3600, "/");
            } 

            // seta os cookies
            if (isset($_POST['remember'])) {
                setcookie('username', $post["email"], time() + (86400 * 30), "/"); // 86400 = 1 dia
                setcookie('password', $post["senha"], time() + (86400 * 30), "/"); // Por motivos de segurança, evite armazenar senhas diretamente em cookies
            } 
            
            return Redirect::page(Formulario::retornaHomeAdminOuHome());

        } else {
            Session::set('msgError', 'Usuário e ou senha inválido.');
            return Redirect::page("home/login");
        }
    }

    /**
     * signOut
     *
     * @return void
     */
    public function signOut()
    {
        Session::destroy('usuarioId');
        Session::destroy('usuarioLogin');
        Session::destroy('usuarioEmail');
        Session::destroy('usuarioNivel');
        Session::destroy('usuarioSenha');
        Session::destroy('id_funcionario');
        Session::destroy('usuarioImagem');

        // Limpar cookies
        // setcookie('username', '', time() - 3600, "/");
        // setcookie('password', '', time() - 3600, "/");
        
        return Redirect::Page("home");
    }

    /**
     * solicitaRecuperacaoSenha - Carrega a view para recuperação de senha
     *
     * @return void
     */
    public function solicitaRecuperacaoSenha() 
    {
        return $this->loadView("usuario/formSolicitaRecuperacaoSenha");
    }

    /*
    *   Envia e-mail com link para recuperação da senha
    */

    public function gerarLinkRecuperaSenha() 
    {
        $post           = $this->getPost();
        $usuarioModel   = $this->loadModel("Usuario");
        
        $user           = $usuarioModel->getUserEmail($post['email']);

        if (!$user) {

            return Redirect::page("Login/solicitaRecuperacaoSenha", [
                "msgError" => "Não foi possivel localizar o e-mail na base de dados !"
            ]);

        } else {

            $created_at = date('Y-m-d H:i:s');
            $chave      = sha1($user['id'] . $user['senha'] . date('YmdHis', strtotime($created_at)));
            $cLink      = baseUrl() . "Login/recuperarSenha/recuperaSenha/" . $chave;

            $corpoEmail = '
                Você solicitou a recuperação de sua senha? <br><br>
                Caso tenha solicitação clique no link a seguir para prosseguir <a href="'. $cLink . '" title="Recuperar a senha">Recuperar a senha</a> <br><br>
                Att: <br><br>
                Equipe Controle estoque
            ';

            $lRetMail = Email::enviaEmail(
                'maycon7ads@gmail.com',                                             /* Email do Remetente*/
                'Controle estoque - Contato',                                       /* Nome do Remetente */
                'Controle estoque - Solicitação de recuperação de senha.',          /* Assunto do e-mail */
                $corpoEmail,                                                        /* Corpo do E-mail */
                $user['email']                                                      /* Destinatário do E-mail */
            );

            if ($lRetMail) {

                // Gravar o link no banco de dados
                $usuarioRecuperaSenhaModel = $this->loadModel("UsuarioRecuperaSenha");

                // Desativando solicitações antigas

                $usuarioRecuperaSenhaModel->desativaChaveAntigas($user["id"]);

                // Inserindo nova solicitação

                $resIns = $usuarioRecuperaSenhaModel->insert([
                    "usuario_id" => $user["id"], 
                    "chave" => $chave,
                    "created_at" => $created_at
                ]);

                if ($resIns) {
                    return Redirect::page("Home/Login", [
                        "msgSuccess" => "Link para recuperação da senha enviado com sucesso! Verifique seu e-mail."
                    ]);   
                } else {
                    return Redirect::page("Login/solicitaRecuperacaoSenha", [
                        "msgError" => "Ocorreu uma falha ao resgistrar o link de recuperação da senha, favor descartar o e-mail recibido e solictar no link mais tarde."
                    ]);   
                }

            } else {

                return Redirect::page("Login/solicitaRecuperacaoSenha", [
                    "msgError" => "Não foi possivel enviar o e-mail, favor tentar mais tarde."
                ]);      

            }
        }
    }


    /**
     * recuperarSenha - Abre formulário para chamada da view de recuperação da senha
     *
     * @return void
     */
    public function recuperarSenha()
    {
        $chave = $this->getid();

        $usuarioRecuperaSenhaModel  = $this->loadModel('UsuarioRecuperaSenha');
        $userChave                  = $usuarioRecuperaSenhaModel->getRecuperaSenhaChave($chave);

        if ($userChave) {

            if (date("Y-m-d H:i:s") <= date("Y-m-d H:i:s" , strtotime("+1 hours" , strtotime($userChave['created_at'])))) {

                $usuarioModel = $this->loadModel('Usuario');
                $user           = $usuarioModel->getById($userChave['usuario_id']);

                if ($user) {

                    $chaveRecSenha = sha1($userChave['usuario_id'] . $user['senha'] . date("YmdHis", strtotime($userChave['created_at'])));

                    if ($chaveRecSenha == $userChave['chave']) {

                        $dbDados = [
                            "id"    => $user['id'],
                            'nome'  => $user['nome'],
                            'usuariorecuperasenha_id' => $userChave['id']
                        ];

                        Session::destroy("msgError");

                        // chave válida

                        return $this->loadView("usuario/formRecuperarSenha", $dbDados);

                        //

                    } else {
                        // Desativa chave
                        $upd = $usuarioRecuperaSenhaModel->desativaChave($userChave['id']);

                        return Redirect::page("Login/solicitaRecuperacaoSenha", [
                            "msgError" => "Chave de recuperação da senha inválida."
                        ]); 
                    }

                } else {

                    // Desativa chave
                    $upd = $usuarioRecuperaSenhaModel->desativaChave($userChave['id']);

                    return Redirect::page("Login/solicitaRecuperacaoSenha", [
                        "msgError" => "Chave de recuperação da senha inválida."
                    ]); 

                }
                
            } else {

                // Desativa chave
                $upd = $usuarioRecuperaSenhaModel->desativaChave($userChave['id']);

                return Redirect::page("Login/solicitaRecuperacaoSenha", [
                    "msgError" => "Chave de recuperação da senha inválida."
                ]); 
            }

        } else {
            return Redirect::page("Login/solicitaRecuperacaoSenha", [
                "msgError" => "Chave de recuperação da senha inválida (1)."
            ]);             
        }
    }

        /**
     * atualizaRecuperaSenha - Atualiza recupera a senha do usuário
     *
     * @return void
     */
    public function atualizaRecuperaSenha() 
    {
        $UsuarioModel = $this->loadModel("Usuario");

        $post       = $this->getPost();
        $userAtual  = $UsuarioModel->getById($post["id"]);

        if ($userAtual) {

            if (trim($post["NovaSenha"]) == trim($post["NovaSenha2"])) {

                if ($UsuarioModel->update(['id' => $post['id']], ['senha' => password_hash(trim($post["NovaSenha"]), PASSWORD_DEFAULT)])) {

                    // Desativa chave
                    $usuarioRecuperaSenhaModel = $this->loadModel('UsuarioRecuperaSenha');

                    $usuarioRecuperaSenhaModel->update(['id' => $post['usuariorecuperasenha_id']], ['statusRegistro' => 2]);
                    //

                    Session::destroy("msgError");
                    return Redirect::page("Home/Login", [
                        "msgSuccess"    => "Senha alterada com sucesso !"
                    ]);  

                } else {
                    Session::set("msgError", "Falha na atualização da nova senha !");
                    return $this->loadView("usuario/formRecuperaSenha"); 
                }

            } else {
                Session::set("msgErros", "Nova senha e conferência da senha estão divergentes !");
                return $this->loadView("usuario/formRecuperaSenha");                   
            }

        } else {
            Session::set("msgErros", "Usuário inválido !");
            return $this->loadView("usuario/formRecuperaSenha"); 
        }
    }
}