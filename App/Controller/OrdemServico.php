<?php

use App\Library\ControllerMain;
use App\Library\Redirect;
use App\Library\Validator;
use App\Library\Session;

class OrdemServico extends ControllerMain
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
        $this->loadView("restrita/listaOrdemServico", $this->model->lista("id"));
    }

    /**
     * form
     *
     * @return void
     */
    public function form()
    {
        $dados = [];

        $PecaModel = $this->loadModel("Produto");
        $dados['aPeca'] = $PecaModel->listaPeca($this->getId());
        
        if ($this->getAcao() != "insert") {
            $registro = $this->model->getById($this->getId());
            if (is_array($registro)) {
                // Mescla os dados de $registro com os dados existentes em $dados
                $dados = array_merge($dados, $registro);
            }
        }
        return $this->loadView("restrita/formOrdemServico", $dados);
    }

    public function getPecaComboBox()
    {

        $dados = $this->model->getPecaCombobox($this->getOutrosParametros(2)); 
    
        echo json_encode($dados);
    
    }

    public function insert()
    {

        $post = $this->getPost();

        // Verifica se todos os campos do formulário foram enviados
        if (
            isset($post['cliente_nome'], $post['telefone_cliente'], $post['modelo_dispositivo'], $post['imei_dispositivo'],
            $post['tipo_servico'], $post['descricao_servico'], $post['problema_reportado'], $post['data_abertura'], 
            $post['status'], $post['observacoes'])
        ) {

            // Dados da ordem de servico
            $cliente_nome = $post['cliente_nome'];
            $telefone_cliente = $post['telefone_cliente'];
            $modelo_dispositivo = $post['modelo_dispositivo'];
            $imei_dispositivo = $post['imei_dispositivo'];
            $tipo_servico = $post['tipo_servico'];
            $descricao_servico = $post['descricao_servico'];
            $problema_reportado = $post['problema_reportado'];
            $data_abertura = $post['data_abertura'];
            $status = $post['status'];
            $observacoes = $post['observacoes'];

            // Dados da peça
            $quantidade = isset($post['quantidade']) ? (int)$post['quantidade'] : '';
            $id_peca = isset($post['id_peca']) ? (int)$post['id_peca'] : '';
            $valor_peca = isset($post['valor']) ? (float)$post['valor'] : '';

            $PecaModel = $this->loadModel("Produto");
            $dadosPeca = $PecaModel->recuperaPeca($id_peca);
          
            if ($this->getAcao() == 'update') {
                
                // Verificar se há uma sessão de ordem de serviço
                if (!isset($_SESSION['ordem_servico'])) {
                    $_SESSION['ordem_servico'] = array();
                }
            
                // Verificar se há produtos na sessão de ordem de serviço
                if (!isset($_SESSION['ordem_servico'][0]['produtos'])) {
                    $_SESSION['ordem_servico'][0]['produtos'] = array();
                }
            
                // Verificar se o produto já está na sessão de ordem de serviço
                $produtoEncontrado = false;
                foreach ($_SESSION['ordem_servico'][0]['produtos'] as &$produto_sessao) {
                    if ($produto_sessao['id_peca'] == $id_peca) {
                        // Atualizar a quantidade do produto na sessão
                        $produto_sessao['quantidade'] += $quantidade;
                        $produtoEncontrado = true;
                        break;
                    }
                }
            } 
            
                // parte da inserção de ordem de serviço e produtos
                $inserindoOrdemServicoEProdutos = $this->model->insertOrdemServico([
                    "cliente_nome"              => $cliente_nome,
                    "telefone_cliente"          => $telefone_cliente,
                    "modelo_dispositivo"        => $modelo_dispositivo,
                    "imei_dispositivo"          => $imei_dispositivo,
                    "tipo_servico"              => $tipo_servico,
                    "descricao_servico"         => $descricao_servico,
                    "problema_reportado"        => $problema_reportado,
                    "status"                    => $status,
                    "data_abertura"             => $data_abertura,
                    "observacoes"               => $observacoes,
                
                ],
                [
                    [
                        // "d_ordem_servico"  => '',
                        "id_peca"           => $id_peca,
                        "quantidade"        => $quantidade,
                    ]
                ]
                );
            
                if($inserindoOrdemServicoEProdutos) {
                    Session::destroy('ordem_servico');
                    Session::destroy('produtos');
                    Session::set("msgSuccess", "Ordem de serviço adicionada com sucesso.");
                    Redirect::page("OrdemServico");
                }
        } else {
            Session::set("msgError", "Dados do formulário insuficientes.");
            Redirect::page("OrdemServico/form/insert/0");
        }
    }

    /**
     * insertProdutoOrdemServico
     *
     * @return void
     */
    public function insertProdutoOrdemServico()
    {
        $post = $this->getPost();
   
        $id_ordem_servico = isset($post['id_ordem_servico']) ? $post['id_ordem_servico'] : ""; 
        $quantidade = $post['quantidade'];
        $id_peca = $post['id_peca'];
        $valor_produto = (float)$post['valor'];

        $PecaModel = $this->loadModel("Produto");
        $dadosPeca['aPeca'] = $PecaModel->recuperaPeca($id_peca);

        // Verificar se há uma sessão de ordem de serviço
        if (!isset($_SESSION['ordem_servico'])) {
            $_SESSION['ordem_servico'] = array();
        }

        // Verificar se há produtos na sessão de ordem de serviço
        if (!isset($_SESSION['ordem_servico'][0]['produtos'])) {
            $_SESSION['ordem_servico'][0]['produtos'] = array();
        }
    
        // Verificar se o produto já está na sessão de ordem de serviço
        $produtoEncontrado = false;
        foreach ($_SESSION['ordem_servico'][0]['produtos'] as &$produto_sessao) {
            if ($produto_sessao['id_peca'] == $id_peca) {
                // Atualizar a quantidade do produto na sessão
                $produto_sessao['quantidade'] += $quantidade;
                $produtoEncontrado = true;
                break;
            }
        }
   
        // Se o produto não estiver na sessão de ordem de serviço, adicioná-lo
        if (!$produtoEncontrado) {
            $_SESSION['ordem_servico'][0]['produtos'][] = array(
                'nome_peca' => $dadosPeca['aPeca'][0]['nome'],
                'id_peca' => $id_peca,
                'quantidade' => $quantidade,
                'valor' => $valor_produto
            );
        }

        Session::set("msgSuccess", "Peça adicionada a ordem de serviço.");
        Redirect::page("OrdemServico/form/insert/0");
    }

    /**
     * update
     *
     * @return void
     */
    public function update()
    {
        $post = $this->getPost();

        if (
            isset($post['cliente_nome']) || isset($post['telefone_cliente']) || isset($post['modelo_dispositivo']) || isset($post['imei_dispositivo']) ||
            isset($post['tipo_servico']) || isset($post['descricao_servico']) || isset($post['problema_reportado']) || isset($post['data_abertura']) || 
            isset($post['status']) || isset($post['observacoes']) || isset($post['quantidade']) || isset($post['id_peca']) || isset($post['valor'])) {
            
            // Dados da ordem de servico
            $id_ordem_servico = isset($post['id']) ? $post['id'] : $post['id_ordem_servico'];
            $cliente_nome = isset($post['cliente_nome']) ? $post['cliente_nome'] : null;
            $telefone_cliente = isset($post['telefone_cliente']) ? $post['telefone_cliente'] : null;
            $modelo_dispositivo = isset($post['modelo_dispositivo']) ? $post['modelo_dispositivo'] : null;
            $imei_dispositivo = isset($post['imei_dispositivo']) ? $post['imei_dispositivo'] : null;
            $tipo_servico = isset($post['tipo_servico']) ? $post['tipo_servico'] : null;
            $descricao_servico = isset($post['descricao_servico']) ? $post['descricao_servico'] : null;
            $problema_reportado = isset($post['problema_reportado']) ? $post['problema_reportado'] : null;
            $data_abertura = isset($post['data_abertura']) ? $post['data_abertura'] : null;
            $status = isset($post['status']) ? $post['status'] : null;
            $observacoes = isset($post['observacoes']) ? $post['observacoes'] : null;

            // Dados da peça
            $quantidade = isset($post['quantidade']) ? (int)$post['quantidade'] : '';
            $id_peca = isset($post['id_peca']) ? (int)$post['id_peca'] : '';
            $valor_peca = isset($post['valor']) ? (float)$post['valor'] : '';
            
            $produtoMovAtualizado = isset($_SESSION['produto_mov_atualizado']) ? $_SESSION['produto_mov_atualizado'] : [];
            
            $found = false;

            (int)$quantidade_peca = (int)$quantidade; 

            $OrdemServicoItemModel = $this->loadModel("OrdemServicoPeca");
            $dadosItensOrdemServico = $OrdemServicoItemModel->recuperaPecaOS($id_peca, $id_ordem_servico);

            $quantidade_ordem_servico = $dadosItensOrdemServico[0]['quantidade'];
            
            if (!empty($dadosItensOrdemServico)) {
             
                foreach ($dadosItensOrdemServico as $item) {
       
                    if ($id_peca == $item['id_peca'] && $id_ordem_servico == $item['id_ordem_servico']) {
                        $acaoProduto = 'update';
                
                        $found = true;
                   
                        break;
                    }
                }     

                if (!$found) {
                    $acaoProduto = 'insert';
                }
         
            } else {
                $quantidade_ordem_servico = (int)$quantidade;
                if(isset($post['id_peca'])) {
                    if ($id_peca == $post['id_peca'] && $id_ordem_servico == $post['id_ordem_servico']) {
                        $acaoProduto = 'insert';
                    }
                }   
            }

            // var_dump($dadosPeca);
            // exit;
            // if (!empty($dadosPeca)) {
            //     if ($dadosPeca[0]['quantidade'] >= $quantidade) {
            //         $verificaQuantidadeEstoqueNegativa = true;
            //     } else if ($dadosPeca[0]['quantidade'] < $quantidade) {
                    $verificaQuantidadeEstoqueNegativa = true;
            //     } 
            // }
      
            if ($this->getAcao() != 'updateProdutoOrdemServico') {

                $AtualizandoOrdemServicoEProdutos = $this->model->updateOrdemServico(
                    [
                        "id"   => $id_ordem_servico
                    ],
                    [
                        "cliente_nome"      => $cliente_nome,
                        "telefone_cliente"  => $telefone_cliente,
                        "modelo_dispositivo"=> $modelo_dispositivo,
                        "imei_dispositivo"  => $imei_dispositivo,
                        "tipo_servico"      => $tipo_servico,
                        "descricao_servico" => $descricao_servico,
                        "problema_reportado"=> $problema_reportado,
                        "status"            => $status,
                        "data_abertura"     => $data_abertura,
                        "observacoes"       => $observacoes,
                    ],
                    [
                        [
                            "id_peca"       => $id_peca,
                            "quantidade"    => $quantidade,
                            "valor"         => $valor_peca
                        ]
                    ],

                );

                if ($AtualizandoOrdemServicoEProdutos || isset($_SESSION['produto_mov_atualizado']) && $_SESSION['produto_mov_atualizado'] == true) {
                    Session::destroy('OrdemServico');
                    Session::destroy('produtos');
                    Session::destroy('produto_mov_atualizado');
                    Session::set("msgSuccess", "Ordem de servico alterada com sucesso.");
                    return Redirect::page("OrdemServico");
                } else {
                    Session::set("msgError", "Falha tentar alterar a Ordem de serviço.");
                    return Redirect::page("OrdemServico/form/update/" . $id_ordem_servico);

                }

            } else if ($this->getAcao() == 'updateProdutoOrdemServico') {

                if ($verificaQuantidadeEstoqueNegativa) {
                    $AtualizandoInfoProdutoOrdemServico = $this->model->updateInformacoesProdutoOrdemServico(
                        [
                            "id_ordem_servico" => $id_ordem_servico
                        ],
                        [
                            [
                                "id_peca"           => $id_peca,
                                "quantidade"        => $quantidade,
                                // "valor"                 => $valor_peca
                            ]
                        ],
                        [
                            'acaoProduto' => $acaoProduto
                        ],
                        $quantidade_ordem_servico
                        
                    );

                    if ($AtualizandoInfoProdutoOrdemServico) {
                        if (!isset($_SESSION['produto_mov_atualizado'])) {
                            $_SESSION['produto_mov_atualizado'] = true;
                        }
                        
                        Session::destroy('OrdemServico');
                        Session::destroy('produtos');
                        Session::set("msgSuccess", "Ordem de servico alterada com sucesso.");
                        return Redirect::page("OrdemServico/form/update/" . $id_ordem_servico); 
                    }

                } else {
                    Session::set("msgError", "Sem produto em estoque.");
                    return Redirect::page("OrdemServico/form/update/" . $id_ordem_servico);
                }
            } else {
                Session::set("msgError", "Falha tentar alterar a Ordem de servico.");
                return Redirect::page("OrdemServico");
            }
        }
    }

    /**
     * deleteProdutoOrdemServico
     *
     * @return void
     */
    public function deleteProdutoOrdemServico()
    {
        $post = $this->getPost();

        $id_ordem_servico = isset($post['id_movimentacao']) ? (int)$post['id_movimentacao'] : ""; 
        $quantidadeRemover = (int)$post['quantidadeRemover'];
        $id_produto = (int)$post['id_produto'];
        $tipo_movimentacao = (int)$post['tipo'];

        if(isset($_SESSION['ordem_servico'][0]['produtos']) && $this->getAcao() == 'delete') {
            // Verificar se o produto já está na sessão de movimentação
            $produtoEncontrado = false;
            foreach ($_SESSION['ordem_servico'][0]['produtos'] as $key => &$produto_sessao) {
                if ($produto_sessao['id_peca'] == $id_produto) {
                    // Atualizar a quantidade do produto na sessão
                    $produto_sessao['quantidade'] -= $quantidadeRemover;

                    if ($produto_sessao['quantidade'] <= 0) {
                        // Remover o produto do array na sessão
                        unset($_SESSION['ordem_servico'][0]['produtos'][$key]);

                    }
                    $produtoEncontrado = true;

                    Session::set("msgSuccess", "Produto excluído da Ordem de serviço.");
                    Redirect::page("OrdemServico/form/insert/0");
                    break;
                }
            }
        }
        
    
        if(!isset($_SESSION['ordem_servico']) && $this->getAcao() == 'delete') {

            $ProdutoModel = $this->loadModel("Produto");
            $dadosPeca = $ProdutoModel->recuperaPeca($id_produto);

            $deletaProduto =  $this->model->deleteInfoProdutoOrdemServico($id_ordem_servico, $dadosPeca, $tipo_movimentacao, $quantidadeRemover);
            // var_dump($dadosPeca,$);
            // exit("Opa");
            if (!isset($_SESSION['produto_mov_atualizado']) && $deletaProduto) {
                $_SESSION['produto_mov_atualizado'] = true;
            }

            if($deletaProduto) {
                Session::set("msgSuccess", "Item deletado da Ordem de serviço.");
                Redirect::page("OrdemServico/form/update/" . $id_ordem_servico);
            }
        }
        
    }

    public function requireimprimirOS() {

        $this->model->imprimirOS($this->getOutrosParametros(2));
    }
    
    /**
     * delete
     *
     * @return void
     */
    public function delete()
    {
        if ($this->model->delete(["id" => $this->getPost('id')])) {
            $this->model->delete_pecas_ordem($this->getId());
            Session::set("msgSuccess", "Ordem de serviço excluída com sucesso.");
        } else {
            Session::set("msgError", "Falha tentar excluir a O|rdem de serviço.");
        }

        Redirect::page("OrdemServico");
    }


}